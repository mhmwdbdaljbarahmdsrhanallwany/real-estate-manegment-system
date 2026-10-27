
@extends('admin.layout.master')

@section('title')
    إدارة الفواتير
@endsection

@section('contect')
<div class="container-fluid" dir="rtl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">تسجيل دفعة جديدة</h3>
        </div>

        <div class="card-body">
            <div id="paymentMessage" class="alert d-none"></div>

            <form id="paymentForm"
                  action="{{ route('admin.payments.store') }}"
                  method="POST">
                @csrf

                <div class="form-group">
                    <label for="invoice_id">الفاتورة</label>
                    <select name="invoice_id" id="invoice_id"
                            class="form-control" required>
                        <option value="">اختر الفاتورة</option>

                        @foreach($invoices as $invoice)
                            <option
                                value="{{ $invoice->invoice_id }}"
                                data-subscription="{{ $invoice->subscription_id }}"
                                data-net="{{ $invoice->net_amount }}"
                                data-paid="{{ $invoice->paid_amount }}"
                                data-remaining="{{ $invoice->remaining_amount }}"
                                data-penalty="{{ $invoice->penalty_amount ?? 0 }}"
                                data-kind="{{ $invoice->invoice_kind_label }}"
                                data-number="{{ $invoice->invoice_number }}"
                                @selected(
                                    (string) $selectedInvoiceId ===
                                    (string) $invoice->invoice_id
                                )
                            >
                                {{ $invoice->invoice_number }}
                                — {{ $invoice->invoice_kind_label }}
                                — المتبقي: {{ number_format($invoice->remaining_amount, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-danger small" data-error="invoice_id"></div>
                </div>

                <input type="hidden" name="subscription_id" id="subscription_id">

                <div id="invoiceDetails" class="alert alert-light border d-none">
                    <p>رقم الفاتورة: <strong id="detailNumber">—</strong></p>
                    <p>نوع الفاتورة: <strong id="detailKind">—</strong></p>
                    <p>صافي الفاتورة: <strong id="detailNet">0.00</strong></p>
                    <p>الغرامة المسجلة: <strong id="detailPenalty">0.00</strong></p>
                    <p>المدفوع سابقًا: <strong id="detailPaid">0.00</strong></p>
                    <p>المتبقي: <strong id="detailRemaining">0.00</strong></p>
                </div>

                <div class="form-group">
                    <label for="amount">مبلغ الدفعة</label>
                    <input type="number" name="amount" id="amount"
                           class="form-control" min="0.01"
                           step="0.01" required>
                    <div class="text-danger small" data-error="amount"></div>
                </div>

                <div class="form-group">
                    <label for="payment_method">طريقة الدفع</label>
                    <select name="payment_method" id="payment_method"
                            class="form-control" required>
                        <option value="cash">نقدًا</option>
                        <option value="bank">تحويل بنكي مؤكد الاستلام</option>
                    </select>
                    <small class="form-text text-muted">
                        لا تسجل التحويل البنكي إلا بعد التأكد من وصوله خارج النظام.
                    </small>
                    <div class="text-danger small" data-error="payment_method"></div>
                </div>

                <div id="cashFields">
                    <div class="form-group">
                        <label for="cashboxe_id">الصندوق النقدي</label>
                        <select name="cashboxe_id" id="cashboxe_id"
                                class="form-control">
                            <option value="">اختر الصندوق</option>
                            @foreach($cashboxes as $cashbox)
                                <option value="{{ $cashbox->cashboxe_id }}">
                                    {{ $cashbox->name }} — {{ $cashbox->code }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-danger small" data-error="cashboxe_id"></div>
                    </div>
                </div>

                <div id="bankFields" class="d-none">
                    <div class="form-group">
                        <label for="transaction_id">رقم العملية البنكية</label>
                        <input type="text" name="transaction_id"
                               id="transaction_id" class="form-control"
                               maxlength="255">
                        <div class="text-danger small" data-error="transaction_id"></div>
                    </div>

                    <div class="form-group">
                        <label for="reference_number">الرقم المرجعي للتحويل</label>
                        <input type="text" name="reference_number"
                               id="reference_number" class="form-control"
                               maxlength="255">
                        <small class="form-text text-muted">
                            أدخل رقم العملية أو الرقم المرجعي على الأقل.
                        </small>
                        <div class="text-danger small" data-error="reference_number"></div>
                    </div>
                </div>

                <button type="submit" id="submitPayment"
                        class="btn btn-success">
                    تسجيل الدفعة
                </button>

                <a href="{{ route('admin.payments.index') }}"
                   class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('paymentForm');
    const invoiceSelect = document.getElementById('invoice_id');
    const methodSelect = document.getElementById('payment_method');
    const amountInput = document.getElementById('amount');
    const cashboxSelect = document.getElementById('cashboxe_id');
    const transactionInput = document.getElementById('transaction_id');
    const referenceInput = document.getElementById('reference_number');
    const cashFields = document.getElementById('cashFields');
    const bankFields = document.getElementById('bankFields');
    const message = document.getElementById('paymentMessage');
    const submitButton = document.getElementById('submitPayment');

    const money = value =>
        Number(value || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    function clearErrors() {
        document.querySelectorAll('[data-error]').forEach(el => {
            el.textContent = '';
        });
    }

    function showMessage(text, success) {
        message.textContent = text;
        message.className = 'alert ' +
            (success ? 'alert-success' : 'alert-danger');
    }

    function updateInvoice() {
        const option = invoiceSelect.selectedOptions[0];

        if (!option || !option.value) {
            document.getElementById('invoiceDetails').classList.add('d-none');
            document.getElementById('subscription_id').value = '';
            amountInput.value = '';
            return;
        }

        document.getElementById('invoiceDetails').classList.remove('d-none');
        document.getElementById('subscription_id').value =
            option.dataset.subscription;

        document.getElementById('detailNumber').textContent =
            option.dataset.number;
        document.getElementById('detailKind').textContent =
            option.dataset.kind;
        document.getElementById('detailNet').textContent =
            money(option.dataset.net);
        document.getElementById('detailPenalty').textContent =
            money(option.dataset.penalty);
        document.getElementById('detailPaid').textContent =
            money(option.dataset.paid);
        document.getElementById('detailRemaining').textContent =
            money(option.dataset.remaining);

        amountInput.max = option.dataset.remaining;
        amountInput.value = option.dataset.remaining;
    }

    function updateMethod() {
        const isBank = methodSelect.value === 'bank';

        cashFields.classList.toggle('d-none', isBank);
        bankFields.classList.toggle('d-none', !isBank);

        cashboxSelect.required = !isBank;
        transactionInput.required = false;
        referenceInput.required = false;
    }

    invoiceSelect.addEventListener('change', updateInvoice);
    methodSelect.addEventListener('change', updateMethod);

    updateInvoice();
    updateMethod();

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        clearErrors();
        message.classList.add('d-none');

        const option = invoiceSelect.selectedOptions[0];
        const amount = Number(amountInput.value);
        const remaining = Number(option?.dataset.remaining || 0);

        if (!option || !option.value) {
            showMessage('يرجى اختيار الفاتورة.', false);
            return;
        }

        if (!Number.isFinite(amount) || amount <= 0 || amount > remaining) {
            showMessage('مبلغ الدفعة يجب أن يكون موجبًا وألا يتجاوز المتبقي.', false);
            return;
        }

        if (
            methodSelect.value === 'bank' &&
            !transactionInput.value.trim() &&
            !referenceInput.value.trim()
        ) {
            showMessage('أدخل رقم العملية البنكية أو الرقم المرجعي.', false);
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'جارٍ تسجيل الدفعة...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            const result = await response.json();

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    Object.entries(result.errors).forEach(([field, errors]) => {
                        const target = document.querySelector(
                            `[data-error="${field}"]`
                        );
                        if (target) target.textContent = errors[0];
                    });

                    showMessage(
                        result.message || 'تحقق من البيانات المدخلة.',
                        false
                    );
                    return;
                }

                showMessage(
                    result.message || 'تعذر تسجيل الدفعة.',
                    false
                );
                return;
            }

            showMessage(
                result.message || 'تم تسجيل الدفعة بنجاح.',
                true
            );

            submitButton.textContent = 'تم التسجيل';

            setTimeout(() => {
                window.location.href = "{{ route('admin.payments.index') }}";
            }, 900);
        } catch (error) {
            showMessage('تعذر الاتصال بالخادم. تحقق من الاتصال ثم أعد المحاولة.', false);
        } finally {
            if (submitButton.textContent !== 'تم التسجيل') {
                submitButton.disabled = false;
                submitButton.textContent = 'تسجيل الدفعة';
            }
        }
    });
});
</script>
