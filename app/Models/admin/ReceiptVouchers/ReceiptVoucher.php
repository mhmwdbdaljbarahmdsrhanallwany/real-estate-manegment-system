<?php

namespace App\Models\Admin\ReceiptVouchers;

use App\Models\Admin\Cashboxes\Cashbox;
use App\Models\Admin\Invoices\Invoice;
use App\Models\Admin\Organizations\Organization;
use App\Models\Admin\Payments\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptVoucher extends Model
{
    protected $table = 'receipt_vouchers';

    protected $primaryKey = 'receipt_voucher_id';

    protected $fillable = [
        'cashboxe_id',
        'invoice_id',
        'payment_id',
        'organization_id',
        'amount',
        'source_type',
        'source_id',
        'received_at',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_at' => 'datetime',
    ];

    /**
     * الصندوق الذي استقبل المبلغ.
     */
    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(
            Cashbox::class,
            'cashboxe_id',
            'cashboxe_id'
        );
    }

    /**
     * الفاتورة المرتبطة بسند القبض.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id',
            'invoice_id'
        );
    }

    /**
     * المؤسسة التي دفعت المبلغ.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id',
            'organization_id'
        );
    }
/**
 * الدفعة المرتبطة بسند القبض.
 */
public function payment(): BelongsTo
{
    return $this->belongsTo(
        Payment::class,
        'payment_id',
        'payment_id'
    );
}
}
