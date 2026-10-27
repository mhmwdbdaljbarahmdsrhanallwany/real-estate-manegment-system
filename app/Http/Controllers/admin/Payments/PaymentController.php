<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Exceptions\PaymentResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentRequest;
use App\Models\Admin\Cashboxes\Cashbox;
use App\Models\Admin\Invoices\Invoice;
use App\Models\Admin\Payments\Payment;
use App\Services\Payments\PaymentCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::query()
            ->latest('payment_id')
            ->paginate(10);
    
        return view('admin.payments.index', compact('payments'));
    }
    public function ajaxSearch(Request $request)
    {
        $query = Payment::query()
            ->latest('payment_id');
    
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
    
            $query->where(function ($q) use ($search) {
                $q->where('payment_id', $search)
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }
    
        if (
            $request->filled('status') &&
            $request->input('status') !== 'all'
        ) {
            $query->where('status', $request->input('status'));
        }
    
        if (
            $request->filled('payment_method') &&
            $request->input('payment_method') !== 'all'
        ) {
            $query->where(
                'payment_method',
                $request->input('payment_method')
            );
        }
    
        $payments = $query->paginate(10)->withQueryString();
    
        return view('admin.payments.ajax_search', compact('payments'));
    }
    public function create()
{
    $invoices = Invoice::query()
        ->with('subscription')
        ->withSum([
            'payments as successful_paid_amount' => function ($query) {
                $query->where('status', 'success');
            },
        ], 'amount')
        ->whereIn('status', ['pending', 'partial', 'overdue'])
        ->orderByDesc('issue_date')
        ->get()
        ->map(function (Invoice $invoice) {
            $netAmount = (float) $invoice->net_amount;
            $paidAmount = (float) ($invoice->successful_paid_amount ?? 0);

            $remainingAmount = max(
                0,
                round($netAmount - $paidAmount, 2)
            );

            $details = $invoice->calculation_details ?? [];

            $invoiceKind = data_get(
                $details,
                'invoice_kind',
                'unknown'
            );

            $invoiceKindLabel = match ($invoiceKind) {
                'general' => 'فاتورة عامة للاشتراك',
                'periodic' => 'فاتورة دورية',
                'penalty' => 'فاتورة غرامة',
                default => 'فاتورة',
            };

            $invoice->setAttribute(
                'invoice_kind_label',
                $invoiceKindLabel
            );

            $invoice->setAttribute(
                'paid_amount',
                $paidAmount
            );

            $invoice->setAttribute(
                'remaining_amount',
                $remainingAmount
            );

            return $invoice;
        })
        ->filter(function (Invoice $invoice) {
            return $invoice->remaining_amount > 0;
        })
        ->values();

        $cashboxes = Cashbox::query()
        ->where('code', 'CASH-MAIN')
        ->where('status', 'active')
        ->get();
    
    $selectedInvoiceId = request()->query('invoice_id');
    
    return view('admin.payments.create', compact(
        'invoices',
        'cashboxes',
        'selectedInvoiceId'
    ));
}
    public function store(
        StorePaymentRequest $request,
        PaymentCollectionService $paymentService
    ): JsonResponse {
        $data = $request->validated();
    
        try {
            $result = $paymentService->collect($data);
        } catch (PaymentResourceNotFoundException $e) {
            return $this->notFoundResponse($e);
        }
    
        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'payment' => $result['payment'],
            'invoice_status' => $result['invoice_status'],
            'paid_amount' => $result['paid_amount'],
            'remaining_amount' => $result['remaining_amount'],
            'cashbox_balance' => $result['cashbox_balance'],
        ], 201);
    }
    
    private function notFoundResponse(
        PaymentResourceNotFoundException $e
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'errors' => [
                $e->field => [$e->getMessage()],
            ],
        ], 404);
    }
    
}
