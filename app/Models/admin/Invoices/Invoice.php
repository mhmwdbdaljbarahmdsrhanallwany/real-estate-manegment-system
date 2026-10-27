<?php

namespace App\Models\Admin\Invoices;

use App\Models\Admin\Subscriptions\Subscription;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Payments\Payment;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Invoice extends Model
{
    protected $table = 'invoices';

    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'subscription_id',
        'invoice_number',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'net_amount',
        'status',
        'issue_date',
        'penalty_amount',
'calculation_details',
        'due_date',
        'billing_period_end',
        'billing_period_start',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    'penalty_amount' => 'decimal:2',
'calculation_details' => 'array',
        'issue_date' => 'date',
        'due_date' => 'date',
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
    ];

    /**
     * الاشتراك المرتبط بالفاتورة.
     */
    public function subscription()
    {
        return $this->belongsTo(
            Subscription::class,
            'subscription_id',
            'subscription_id'
        );
    }
    /**
 * الدفعات المرتبطة بالفاتورة.
 */
public function payments(): HasMany
{
    return $this->hasMany(
        Payment::class,
        'invoice_id',
        'invoice_id'
    );
}
}
