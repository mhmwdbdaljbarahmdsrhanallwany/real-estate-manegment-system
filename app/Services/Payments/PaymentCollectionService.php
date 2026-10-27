<?php

namespace App\Services\Payments;

use App\Exceptions\PaymentResourceNotFoundException;
use App\Models\Admin\Cashboxes\Cashbox;
use App\Models\Admin\Invoices\Invoice;
use App\Models\Admin\Payments\Payment;
use App\Models\Admin\Payments\PaymentAllocation;
use App\Models\Admin\ReceiptVouchers\ReceiptVoucher;
use App\Models\Admin\Subscriptions\Subscription;
use App\Models\Admin\Transactions\Transaction as FinancialTransaction;
use App\Services\Invoices\InvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentCollectionService
{
    private const CASHBOX_CASH = 'CASH-MAIN';
    private const CASHBOX_BANK = 'BANK-MAIN';

    /**
     * تسجيل دفعة نقدية أو تحويل بنكي مؤكد.
     *
     * يجب التحقق الفعلي من وصول التحويل البنكي قبل استدعاء هذه الخدمة.
     */
    public function collect(array $data): array
    {
        if (!in_array($data['payment_method'] ?? null, ['cash', 'bank'], true)) {
            throw ValidationException::withMessages([
                'payment_method' => 'طريقة الدفع غير مدعومة حاليًا.',
            ]);
        }
        foreach (['transaction_id', 'reference_number'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $value = trim($data[$field]);
                $data[$field] = $value === '' ? null : $value;
            }
        }

        return DB::transaction(function () use ($data) {
            // قفل الفاتورة لمنع تجاوز المبلغ المستحق عند التحصيل المتزامن.
            $invoice = Invoice::query()
                ->where('invoice_id', $data['invoice_id'])
                ->lockForUpdate()
                ->first();

            if (!$invoice) {
                throw new PaymentResourceNotFoundException(
                    'invoice_id',
                    'الفاتورة المحددة غير موجودة.'
                );
            }

            $subscription = Subscription::query()
            ->where('subscription_id', $data['subscription_id'])
            ->lockForUpdate()
            ->first();

            if (!$subscription) {
                throw new PaymentResourceNotFoundException(
                    'subscription_id',
                    'الاشتراك المحدد غير موجود.'
                );
            }

            if ((int) $invoice->subscription_id !== (int) $subscription->subscription_id) {
                throw ValidationException::withMessages([
                    'subscription_id' => 'الاشتراك المحدد لا يتبع الفاتورة المختارة.',
                ]);
            }

            if (!$subscription->organization_id) {
                throw ValidationException::withMessages([
                    'subscription_id' => 'لا توجد مؤسسة مرتبطة بالاشتراك.',
                ]);
            }

            $allowedStatuses = ['pending', 'partial', 'overdue'];

if (!in_array($invoice->status, $allowedStatuses, true)) {
    throw ValidationException::withMessages([
        'invoice_id' => 'حالة الفاتورة لا تسمح باستقبال دفعات.',
    ]);
}

            $paidCents = $this->successfulPaidCents(
                (int) $invoice->invoice_id
            );

            $netCents = $this->toCents($invoice->net_amount);
            $remainingCents = $netCents - $paidCents;
            $amountCents = $this->toCents($data['amount']);

            if ($remainingCents <= 0 || $amountCents > $remainingCents) {
                throw ValidationException::withMessages([
                    'amount' => 'المبلغ المطلوب أكبر من المتبقي على الفاتورة، والمتبقي هو '
                        . $this->formatMoney(max(0, $remainingCents))
                        . '.',
                ]);
            }

            $isCash = $data['payment_method'] === 'cash';

            // اختيار الصندوق من الخادم، وعدم قبول صندوق يختاره العميل للتحويل البنكي.
            $cashboxQuery = Cashbox::query()
                ->where(
                    'code',
                    $isCash ? self::CASHBOX_CASH : self::CASHBOX_BANK
                )
                ->where('status', 'active');

            if ($isCash) {
                $cashboxQuery->where(
                    'cashboxe_id',
                    $data['cashboxe_id']
                );
            }

            $cashbox = $cashboxQuery
                ->lockForUpdate()
                ->first();

            if (!$cashbox) {
                throw ValidationException::withMessages([
                    'cashboxe_id' => $isCash
                        ? 'الصندوق النقدي الرئيسي غير موجود أو غير نشط.'
                        : 'الصندوق البنكي الرئيسي غير موجود أو غير نشط.',
                ]);
            }
            if (!$isCash) {
                $this->ensureBankTransferIsNotDuplicated($data);
            }

            // لا تُنشأ دفعات معلقة من هذا المسار.
            $payment = Payment::create([
                'invoice_id' => $invoice->invoice_id,
                'subscription_id' => $subscription->subscription_id,
                'amount' => $this->formatMoney($amountCents),
                'payment_method' => $data['payment_method'],
                'transaction_id' => $data['transaction_id'] ?? null,
                'metadata' => $data['metadata'] ?? null,
                'reference_number' => $data['reference_number'] ?? null,
                'paid_at' => now(),
                'status' => 'success',
            ]);

            // إنشاء السند والحركة المالية وتحديث الصندوق والفاتورة.
            $this->postSuccessfulPayment(
                $payment,
                $invoice,
                $subscription,
                $cashbox
            );
            
            // توزيع الفاتورة العامة على فترات الاشتراك بعد نجاح الدفع.
            if (
                data_get($invoice->calculation_details, 'invoice_kind')
                === 'general'
            ) {
                $this->allocateSuccessfulPaymentToPeriods(
                    $payment,
                    $subscription
                );
            }
            
            $invoice->refresh();

            $newPaidCents = $this->successfulPaidCents(
                (int) $invoice->invoice_id
            );

            return [
                'payment' => $payment->fresh(),
                'invoice_status' => $invoice->status,
                'paid_amount' => $this->moneyNumber($newPaidCents),
                'remaining_amount' => $this->moneyNumber(
                    max(
                        0,
                        $this->toCents($invoice->net_amount) - $newPaidCents
                    )
                ),
                'cashbox_balance' => $cashbox->fresh()->current_balance,
                'message' => $isCash
                    ? 'تم تسجيل الدفعة النقدية وترحيلها ماليًا بنجاح.'
                    : 'تم تسجيل التحويل البنكي المؤكد وترحيله ماليًا بنجاح.',
            ];
        }, 3);
    }
    

/**
 * التحقق من عدم تسجيل رقم العملية أو المرجع البنكي سابقًا.
 * تُستدعى داخل المعاملة وبعد قفل الصندوق البنكي.
 */
private function ensureBankTransferIsNotDuplicated(array $data): void
{
    $transactionId = trim((string) ($data['transaction_id'] ?? ''));
    $referenceNumber = trim((string) ($data['reference_number'] ?? ''));

    if ($transactionId === '' && $referenceNumber === '') {
        throw ValidationException::withMessages([
            'reference_number' =>
                'يرجى إدخال رقم العملية البنكية أو الرقم المرجعي للتحويل.',
        ]);
    }

    $query = Payment::query()
        ->where('payment_method', 'bank')
        ->where('status', 'success')
        ->where(function ($q) use ($transactionId, $referenceNumber) {
            if ($transactionId !== '') {
                $q->where('transaction_id', $transactionId);
            }

            if ($referenceNumber !== '') {
                $method = $transactionId !== '' ? 'orWhere' : 'where';

                $q->{$method}('reference_number', $referenceNumber);
            }
        });

        if ($query->lockForUpdate()->first()) {
        throw ValidationException::withMessages([
            'reference_number' =>
                'رقم العملية أو المرجع مسجل مسبقًا ضمن دفعة بنكية ناجحة.',
        ]);
    }
}

/**
 * توزيع الدفعة الناجحة على فترات الاشتراك من الأقدم إلى الأحدث.
 *
 * تُستدعى داخل المعاملة نفسها بعد تسجيل الأثر المالي.
 * لا تنشئ سند قبض أو حركة مالية.
 */
private function allocateSuccessfulPaymentToPeriods(
    Payment $payment,
    Subscription $subscription
): void {
    $subscription->loadMissing('package');

    $package = $subscription->package;

    if (!$package) {
        throw ValidationException::withMessages([
            'subscription_id' => 'لا توجد باقة مرتبطة بالاشتراك لحساب فترات الفوترة.',
        ]);
    }

    $invoiceService = app(InvoiceService::class);

    // استخدام حاسبة الفترات الموجودة بالفعل، دون إنشاء حاسبة جديدة.
    $periods = $invoiceService->calculateBillingPeriods(
        $subscription,
        $package
    );

    if (empty($periods)) {
        throw ValidationException::withMessages([
            'invoice_id' => 'تعذر تحديد فترات الفوترة لهذا الاشتراك.',
        ]);
    }

    $remainingCents = $this->toCents($payment->amount);

    foreach ($periods as $period) {
        if ($remainingCents <= 0) {
            break;
        }

        $periodStart = $period['billing_period_start'];
        $periodEnd = $period['billing_period_end'];

        // قيمة الفترة الأصلية المحسوبة من حاسبة الفوترة.
        $periodAmountCents = $this->toCents(
            $period['amount']
        );

        // إجمالي ما خُصص سابقًا لهذه الفترة، من جميع الدفعات.
        $allocatedCents = $this->toCents(
            PaymentAllocation::query()
                ->where(
                    'subscription_id',
                    $subscription->subscription_id
                )
                ->where('period_start', $periodStart)
                ->where('period_end', $periodEnd)
                ->sum('allocated_amount')
        );

        $periodRemainingCents = max(
            0,
            $periodAmountCents - $allocatedCents
        );

        if ($periodRemainingCents === 0) {
            continue;
        }

        // لا نخصص أكثر من المتبقي من الدفعة أو الفترة.
        $allocatedNowCents = min(
            $remainingCents,
            $periodRemainingCents
        );

        PaymentAllocation::create([
            'payment_id' => $payment->payment_id,
            'subscription_id' => $subscription->subscription_id,
            'period_number' => $period['period_number'],
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'allocated_amount' => $this->formatMoney(
                $allocatedNowCents
            ),
        ]);

        $remainingCents -= $allocatedNowCents;
    }

    // أي مبلغ لم يجد فترة مستحقة يعني وجود تعارض في الحسابات.
    // رمي الاستثناء داخل المعاملة يؤدي إلى التراجع عن العملية كلها.
    if ($remainingCents > 0) {
        throw ValidationException::withMessages([
            'amount' => 'تعذر توزيع كامل الدفعة على الفترات المتبقية. لم تُعتمد العملية.',
        ]);
    }
}

    /**
     * ترحيل الأثر المالي لدفعة ناجحة.
     * يجب استدعاؤها داخل DB::transaction فقط.
     */
    private function postSuccessfulPayment(
        Payment $payment,
        Invoice $invoice,
        Subscription $subscription,
        Cashbox $cashbox
    ): void {
        $receiptVoucher = ReceiptVoucher::create([
            'cashboxe_id' => $cashbox->cashboxe_id,
            'invoice_id' => $invoice->invoice_id,
            'payment_id' => $payment->payment_id,
            'organization_id' => $subscription->organization_id,
            'amount' => $payment->amount,
            'source_type' => 'invoice',
            'source_id' => $invoice->invoice_id,
            'received_at' => $payment->paid_at ?? now(),
            'description' => 'سند قبض للدفعة رقم ' . $payment->payment_id,
        ]);

        $newBalanceCents = $this->toCents($cashbox->current_balance)
            + $this->toCents($payment->amount);

        $cashbox->current_balance = $this->formatMoney($newBalanceCents);
        $cashbox->save();

        FinancialTransaction::create([
            'cashboxe_id' => $cashbox->cashboxe_id,
            'type' => 'in',
            'amount' => $payment->amount,
            'reference_type' => 'receipt_voucher',
            'reference_id' => $receiptVoucher->receipt_voucher_id,
            'balance_after' => $cashbox->current_balance,
            'currency' => $cashbox->currency,
            'user_id' => auth()->id(),
            'notes' => ($payment->payment_method === 'bank'
                ? 'تحصيل حوالة بنكية للدفعة رقم '
                : 'قبض نقدي للدفعة رقم ')
                . $payment->payment_id,
        ]);

        $paidCents = $this->successfulPaidCents(
            (int) $invoice->invoice_id
        );

        $invoice->status = $paidCents >= $this->toCents($invoice->net_amount)
            ? 'paid'
            : 'partial';

        $invoice->save();
    }

    /**
     * إجمالي الدفعات الناجحة بالسنت/القرش.
     */
    private function successfulPaidCents(int $invoiceId): int
    {
        $sum = Payment::query()
            ->where('invoice_id', $invoiceId)
            ->where('status', 'success')
            ->sum('amount');

        return $this->toCents($sum);
    }

    /**
     * تحويل مبلغ عشري إلى عدد صحيح من الوحدات الصغرى.
     */
    private function toCents(mixed $value): int
    {
        $value = trim((string) $value);

        if (!preg_match('/^(-?)(\d+)(?:\.(\d{1,2}))?$/', $value, $matches)) {
            throw new \UnexpectedValueException(
                'تعذر تحويل قيمة مالية إلى وحدات نقدية صحيحة.'
            );
        }

        $whole = (int) $matches[2];
        $fraction = (int) str_pad($matches[3] ?? '', 2, '0');

        $cents = ($whole * 100) + $fraction;

        return ($matches[1] ?? '') === '-' ? -$cents : $cents;
    }

    /**
     * تنسيق الوحدات الصغرى لقيمة عشرية ذات منزلتين.
     */
    private function formatMoney(int $cents): string
    {
        $sign = $cents < 0 ? '-' : '';
        $absolute = abs($cents);

        return $sign
            . intdiv($absolute, 100)
            . '.'
            . str_pad(
                (string) ($absolute % 100),
                2,
                '0',
                STR_PAD_LEFT
            );
    }

    /**
     * إخراج رقمي متوافق مع الاستجابة الحالية.
     */
    private function moneyNumber(int $cents): float
    {
        return (float) $this->formatMoney($cents);
    }
}
