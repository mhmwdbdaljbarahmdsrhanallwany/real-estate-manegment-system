
@extends('admin.layout.master')

@section('title')
    تفاصيل الفاتورة
@endsection

@section('contect')

@php
    $subscription = $invoice->subscription;
    $organization = $subscription?->organization;
    $package = $subscription?->package;

    $invoiceStatus = match ($invoice->status) {
        'pending' => 'بانتظار السداد',
        'paid' => 'مدفوعة',
        'partial' => 'مدفوعة جزئيًا',
        'overdue' => 'متأخرة',
        default => $invoice->status,
    };

    $statusClass = match ($invoice->status) {
        'paid' => 'paid',
        'partial' => 'partial',
        'overdue' => 'overdue',
        default => 'pending',
    };

    $money = fn ($amount) => number_format((float) $amount, 2);

    $issueDate = $invoice->issue_date
        ? $invoice->issue_date->format('Y-m-d')
        : '—';

    $dueDate = $invoice->due_date
        ? $invoice->due_date->format('Y-m-d')
        : 'غير محدد';
@endphp

<div class="invoice-page" dir="rtl">

    {{-- أدوات التحكم --}}
    @if(empty($printMode))
        <div class="invoice-toolbar">
            <h4>تفاصيل الفاتورة</h4>

            <div class="invoice-toolbar-actions">
                <button type="button"
                        class="btn btn-primary"
                        onclick="window.print()">
                    <i class="fas fa-print"></i>
                    طباعة الفاتورة
                </button>

                <a href="{{ route('admin.subscriptions.show', ['id' => $invoice->subscription_id]) }}"
                   class="btn btn-outline-primary">
                    <i class="fas fa-eye"></i>
                    عرض الاشتراك
                </a>
                @if(in_array($invoice->status, ['pending', 'partial', 'overdue'], true))
    <a href="{{ route('admin.payments.create', [
        'invoice_id' => $invoice->invoice_id,
    ]) }}"
       class="btn btn-success">
        <i class="fas fa-money-bill-wave"></i>
        تسجيل دفعة
    </a>
@endif
            </div>
        </div>
    @endif

    {{-- ورقة الفاتورة --}}
    <article class="invoice-sheet">

        {{-- رأس الفاتورة --}}
        <header class="invoice-heading">

            <div class="issuer">
                <img
                    src="{{ asset('assets/logo/favicon.png') }}"
                    alt="شعار SmarteState"
                    class="issuer-logo"
                >

                <div class="issuer-information">
                    <h1 class="issuer-name">SmarteState</h1>
                    <p class="issuer-description">
                        منصة إدارة المؤسسات العقارية
                    </p>
                </div>
            </div>

            <div class="document-title">
                <h2>فاتورة</h2>

                <div class="invoice-number">
                    رقم الفاتورة:
                    <strong>{{ $invoice->invoice_number }}</strong>
                </div>

                <span class="status-label {{ $statusClass }}">
                    {{ $invoiceStatus }}
                </span>
            </div>

        </header>

        {{-- بيانات الفاتورة --}}
        <section class="invoice-details">

            <div class="detail-item">
                <span>تاريخ الإصدار</span>
                <strong>{{ $issueDate }}</strong>
            </div>

            <div class="detail-item">
                <span>تاريخ الاستحقاق</span>
                <strong>{{ $dueDate }}</strong>
            </div>

            <div class="detail-item">
                <span>رقم الاشتراك</span>
                <strong>{{ $invoice->subscription_id }}</strong>
            </div>

        </section>

        {{-- بيانات العميل --}}
        <section class="customer-section">

            <h3 class="section-heading">بيانات العميل</h3>

            <div class="customer-name">
                {{ $organization?->name ?? 'غير متوفر' }}
            </div>

            @if($organization?->email)
                <div class="customer-detail">
                    <strong>البريد الإلكتروني:</strong>
                    {{ $organization->email }}
                </div>
            @endif

            @if($organization?->phone)
                <div class="customer-detail">
                    <strong>رقم الهاتف:</strong>
                    {{ $organization->phone }}
                </div>
            @endif

        </section>

        {{-- تفاصيل الخدمة --}}
        <section class="services-section">

            <h3 class="section-heading">تفاصيل الخدمة</h3>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th class="col-number">م</th>
                        <th class="col-description">وصف الخدمة</th>
                        <th class="col-amount">المبلغ الأساسي</th>
                        <th class="col-amount">الخصم</th>
                        <th class="col-amount">الضريبة</th>
                        <th class="col-amount">الصافي</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="text-center">1</td>

                        <td>
                            <strong>اشتراك منصة SmarteState</strong>

                            <div class="service-description">
                                الباقة:
                                {{ $package?->name ?? 'غير متوفر' }}
                            </div>

                            <div class="service-description">
                                نوع الاشتراك:
                                @switch($subscription?->subscription_type)
                                    @case('trial')
                                        تجريبي
                                        @break

                                    @case('paid')
                                        مدفوع
                                        @break

                                    @default
                                        غير محدد
                                @endswitch
                            </div>
                        </td>

                        <td class="amount">
                            {{ $money($invoice->total_amount) }}
                        </td>

                        <td class="amount">
                            {{ $money($invoice->discount_amount) }}
                        </td>

                        <td class="amount">
                            {{ $money($invoice->tax_amount) }}
                        </td>

                        <td class="amount">
                            {{ $money($invoice->net_amount) }}
                        </td>
                    </tr>
                </tbody>
            </table>

        </section>

        {{-- ملخص المبالغ --}}
        <section class="totals-section">

            <div class="totals-box">

                <div class="total-row">
                    <span>المبلغ الأساسي</span>
                    <strong>{{ $money($invoice->total_amount) }}</strong>
                </div>

                <div class="total-row">
                    <span>الخصم</span>
                    <strong>{{ $money($invoice->discount_amount) }}</strong>
                </div>

                <div class="total-row">
                    <span>الضريبة</span>
                    <strong>{{ $money($invoice->tax_amount) }}</strong>
                </div>

                <div class="total-row grand-total">
                    <span>إجمالي الفاتورة</span>
                    <strong>{{ $money($invoice->net_amount) }}</strong>
                </div>

            </div>

        </section>

        {{-- ملاحظات الاشتراك --}}
        @if($subscription?->notes)
            <section class="notes-section">
                <h3 class="section-heading">ملاحظات</h3>
                <p>{{ $subscription->notes }}</p>
            </section>
        @endif

        {{-- تذييل الفاتورة --}}
        <footer class="invoice-footer">
            <p>شكرًا لثقتكم بنا.</p>
            <span>
                تم إنشاء هذه الفاتورة إلكترونيًا بواسطة منصة SmarteState.
            </span>
        </footer>

    </article>

</div>

<style>
    /* =========================================
       تنسيقات الشاشة
    ========================================= */

    .invoice-page {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        color: #222;
        font-family: Tahoma, Arial, sans-serif;
        font-size: 13px;
        direction: rtl;
    }

    .invoice-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .invoice-toolbar h4 {
        margin: 0;
        font-size: 20px;
    }

    .invoice-toolbar-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .invoice-sheet {
        width: 100%;
        max-width: 190mm;
        min-height: 250mm;
        margin: 0 auto 24px;
        padding: 12mm;
        box-sizing: border-box;
        background: #fff;
        color: #222;
        border: 1px solid #d9dee5;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        direction: rtl;
    }

    .invoice-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding-bottom: 18px;
        border-bottom: 2px solid #243b53;
    }

    .issuer {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .issuer-logo {
        display: block;
        width: 58px;
        height: 58px;
        object-fit: contain;
        flex-shrink: 0;
    }

    .issuer-name {
        margin: 0 0 6px;
        color: #243b53;
        font-size: 24px;
        font-weight: 700;
    }

    .issuer-description {
        margin: 0;
        color: #666;
        font-size: 11px;
    }

    .document-title {
        min-width: 0;
        text-align: left;
    }

    .document-title h2 {
        margin: 0 0 8px;
        color: #243b53;
        font-size: 28px;
        font-weight: 700;
    }

    .invoice-number {
        margin-bottom: 8px;
        font-size: 11px;
        overflow-wrap: anywhere;
    }

    .status-label {
        display: inline-block;
        padding: 4px 8px;
        border: 1px solid #aaa;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-label.paid {
        color: #17663a;
        border-color: #6da987;
        background: #f1faf4;
    }

    .status-label.partial {
        color: #155b75;
        border-color: #77b6c8;
        background: #f0f9fc;
    }

    .status-label.pending {
        color: #805b12;
        border-color: #d8bc77;
        background: #fffaf0;
    }

    .status-label.overdue {
        color: #9d2525;
        border-color: #d89a9a;
        background: #fff4f4;
    }

    .invoice-details {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        padding: 16px 0;
        border-bottom: 1px solid #d9dee5;
    }

    .detail-item {
        min-width: 0;
    }

    .detail-item span {
        display: block;
        margin-bottom: 6px;
        color: #666;
        font-size: 11px;
    }

    .detail-item strong {
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .customer-section,
    .services-section,
    .notes-section {
        margin-top: 22px;
    }

    .section-heading {
        margin: 0 0 12px;
        padding-bottom: 7px;
        border-bottom: 1px solid #d9dee5;
        color: #243b53;
        font-size: 14px;
        font-weight: 700;
    }

    .customer-name {
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .customer-detail {
        margin-top: 5px;
        overflow-wrap: anywhere;
    }

    /* =========================================
       جدول الفاتورة
    ========================================= */

    .services-section {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    .invoice-table {
        width: 100%;
        max-width: 100%;
        margin: 0;
        border-collapse: collapse;
        table-layout: fixed;
        direction: rtl;
        font-size: 10px;
        box-sizing: border-box;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 8px 4px;
        border: 1px solid #cfd5dc;
        text-align: right;
        vertical-align: top;
        box-sizing: border-box;
        overflow-wrap: anywhere;
        word-break: normal;
    }

    .invoice-table thead th {
        background: #f1f4f7;
        color: #243b53;
        font-weight: 700;
    }

    .invoice-table .col-number {
        width: 6%;
        text-align: center;
    }

    .invoice-table .col-description {
        width: 30%;
    }

    .invoice-table .col-amount {
        width: 16%;
    }

    .invoice-table .text-center {
        text-align: center;
    }

    .invoice-table .amount {
        text-align: left;
        direction: ltr;
        white-space: normal;
        font-variant-numeric: tabular-nums;
    }

    .service-description {
        margin-top: 5px;
        color: #666;
        font-size: 10px;
        overflow-wrap: anywhere;
    }

    /* =========================================
       إجماليات الفاتورة
    ========================================= */

    .totals-section {
        display: flex;
        justify-content: flex-start;
        width: 100%;
        margin-top: 18px;
        direction: rtl;
        box-sizing: border-box;
    }

    .totals-box {
        width: 100%;
        max-width: 320px;
        margin-right: auto;
        margin-left: 0;
        box-sizing: border-box;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 9px 10px;
        border-bottom: 1px solid #e0e4e8;
        box-sizing: border-box;
    }

    .total-row strong {
        direction: ltr;
        text-align: left;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .grand-total {
        margin-top: 4px;
        border-top: 2px solid #243b53;
        border-bottom: 2px solid #243b53;
        background: #f1f4f7;
        font-size: 14px;
        font-weight: 700;
    }

    .notes-section p {
        margin: 0;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .invoice-footer {
        margin-top: 35px;
        padding-top: 12px;
        border-top: 1px solid #cfd5dc;
        text-align: center;
        color: #555;
    }

    .invoice-footer p {
        margin-bottom: 5px;
        font-weight: 700;
    }

    .invoice-footer span {
        font-size: 10px;
    }

    @media screen and (max-width: 767px) {
        .invoice-sheet {
            padding: 16px;
        }

        .invoice-heading {
            flex-direction: column;
        }

        .document-title {
            text-align: right;
        }

        .invoice-details {
            grid-template-columns: 1fr;
        }

        .invoice-toolbar-actions {
            width: 100%;
        }
    }

    /* =========================================
       الطباعة: كتلة واحدة فقط
    ========================================= */

    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    @media print {
        html,
        body {
            width: auto !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            background: #fff !important;
        }

        body * {
            visibility: hidden !important;
        }

        .invoice-page,
        .invoice-page * {
            visibility: visible !important;
        }

        .invoice-toolbar {
            display: none !important;
        }

        .invoice-page {
            position: static !important;
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            direction: rtl !important;
        }

        .invoice-sheet {
            position: relative !important;
            display: block !important;
            inset: auto !important;
            float: none !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 5mm !important;
            border: none !important;
            box-shadow: none !important;
            box-sizing: border-box !important;
            overflow: visible !important;
            background: #fff !important;
            direction: rtl !important;
        }

        .invoice-heading {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: flex-start !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .issuer {
            min-width: 0 !important;
        }

        .document-title {
            min-width: 0 !important;
        }

        .invoice-details {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .customer-section,
        .services-section,
        .notes-section,
        .invoice-footer {
            width: 100% !important;
            max-width: 100% !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
            box-sizing: border-box !important;
        }

        .invoice-table {
            display: table !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            direction: rtl !important;
            box-sizing: border-box !important;
        }

        .invoice-table thead {
            display: table-header-group !important;
        }

        .invoice-table tbody {
            display: table-row-group !important;
        }

        .invoice-table tr {
            display: table-row !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .invoice-table th,
        .invoice-table td {
            display: table-cell !important;
            padding: 5px 3px !important;
            border: 1px solid #999 !important;
            color: #000 !important;
            white-space: normal !important;
            overflow-wrap: anywhere !important;
            box-sizing: border-box !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .invoice-table .col-number {
            width: 6% !important;
        }

        .invoice-table .col-description {
            width: 30% !important;
        }

        .invoice-table .col-amount {
            width: 16% !important;
        }

        .invoice-table .amount {
            text-align: left !important;
            direction: ltr !important;
            white-space: normal !important;
            font-size: 9px !important;
        }

        .totals-section {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 15px 0 0 !important;
            padding: 0 !important;
            direction: rtl !important;
            box-sizing: border-box !important;
        }

        .totals-box {
            width: 85% !important;
            max-width: 320px !important;
            margin-right: auto !important;
            margin-left: 0 !important;
            box-sizing: border-box !important;
        }

        .total-row {
            display: flex !important;
            justify-content: space-between !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .invoice-heading,
        .invoice-details,
        .customer-section,
        .services-section,
        .totals-section,
        .notes-section,
        .invoice-footer {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .status-label {
            background: #fff !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        a {
            color: #000 !important;
            text-decoration: none !important;
        }
    }
</style>

@if(!empty($printMode))
    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
@endif

@endsection
