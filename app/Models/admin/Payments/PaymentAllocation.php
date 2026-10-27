<?php

namespace App\Models\Admin\Payments;

use App\Models\Admin\Subscriptions\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAllocation extends Model
{
    protected $table = 'payment_allocations';

    protected $fillable = [
        'payment_id',
        'subscription_id',
        'period_number',
        'period_start',
        'period_end',
        'allocated_amount',
    ];

    protected $casts = [
        'period_number' => 'integer',
        'period_start' => 'date',
        'period_end' => 'date',
        'allocated_amount' => 'decimal:2',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(
            Payment::class,
            'payment_id',
            'payment_id'
        );
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(
            Subscription::class,
            'subscription_id',
            'subscription_id'
        );
    }
}
