<?php

namespace App\Models\Admin\Payments;

use App\Models\Admin\Invoices\Invoice;
use App\Models\Admin\Subscriptions\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'invoice_id',
        'subscription_id',
        'amount',
        'payment_method',
        'transaction_id',
        'metadata',
        'reference_number',
        'paid_at',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * الفاتورة المرتبطة بالدفع.
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
     * الاشتراك المرتبط بالدفع.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(
            Subscription::class,
            'subscription_id',
            'subscription_id'
        );
    }
    public function allocations(): HasMany
{
    return $this->hasMany(
        PaymentAllocation::class,
        'payment_id',
        'payment_id'
    );
}
}
