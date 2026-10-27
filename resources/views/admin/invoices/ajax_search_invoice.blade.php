
@if (isset($invoices) && $invoices->count() > 0)

    <table class="table table-hover table-bordered align-middle text-center">

        <thead class="table-primary">
            <tr>
                <th>المعرف</th>
                <th>رقم الفاتورة</th>
                <th>المؤسسة</th>
                <th>الباقة</th>
                <th>الإجمالي</th>
                <th>الخصم</th>
                <th>الضريبة</th>
                <th>صافي المبلغ</th>
                <th>تاريخ الإصدار</th>
                <th>تاريخ الاستحقاق</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($invoices as $info)
                @php
                    $statusClass = match ($info->status) {
                        'pending' => 'bg-warning text-dark',
                        'paid' => 'bg-success',
                        'partial' => 'bg-info text-dark',
                        'overdue' => 'bg-danger',
                        default => 'bg-secondary',
                    };

                    $statusLabel = match ($info->status) {
                        'pending' => 'معلقة',
                        'paid' => 'مدفوعة',
                        'partial' => 'مدفوعة جزئيًا',
                        'overdue' => 'متأخرة',
                        default => 'غير معروف',
                    };
                @endphp

                <tr>
                    <td>{{ $info->invoice_id }}</td>

                    <td>{{ $info->invoice_number }}</td>

                    <td>
                        {{ $info->subscription?->organization?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $info->subscription?->package?->name ?? '-' }}
                    </td>

                    <td>{{ number_format((float) $info->total_amount, 2) }}</td>

                    <td>{{ number_format((float) $info->discount_amount, 2) }}</td>

                    <td>{{ number_format((float) $info->tax_amount, 2) }}</td>

                    <td class="fw-bold">
                        {{ number_format((float) $info->net_amount, 2) }}
                    </td>

                    <td>
                        {{ $info->issue_date?->format('Y-m-d') ?? '-' }}
                    </td>

                    <td>
                        {{ $info->due_date?->format('Y-m-d') ?? 'غير محدد' }}
                    </td>

                    <td>
                        <span class="badge {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap " style="gap: 8px;">
                            <a
                                href="{{ route('admin.invoices.show', ['id' => $info->invoice_id]) }}"
                                class="btn btn-sm btn-info"
                                title="عرض الفاتورة"
                            >
                                <i class="fas fa-eye"></i>
                            </a>
                    
                            <a
                                href="{{ route('admin.invoices.print', ['id' => $info->invoice_id]) }}"
                                class="btn btn-sm btn-secondary"
                                title="طباعة الفاتورة"
                                target="_blank"
                            >
                                <i class="fas fa-print"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            إظهار {{ $invoices->firstItem() }}
            إلى {{ $invoices->lastItem() }}
            من أصل {{ $invoices->total() }} فاتورة
        </div>

        <div id="ajax_search">
            {{ $invoices->links() }}
        </div>
    </div>

@else
    <p class="text-center text-danger mt-3">
        لا توجد فواتير لعرضها
    </p>
@endif

