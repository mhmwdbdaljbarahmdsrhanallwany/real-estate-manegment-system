<?php

namespace App\Models\Admin\PaymentVouchers;

use App\Models\Admin\Cashboxes\Cashbox;
use App\Models\Admin\Organizations\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentVoucher extends Model
{
    protected $table = 'payment_vouchers';

    protected $primaryKey = 'payment_voucher_id';

    protected $fillable = [
        'cashboxe_id',
        'organization_id',
        'amount',
        'destination_type',
        'destination_id',
        'paid_at',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * الصندوق الذي خرج منه المبلغ.
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
     * المؤسسة المرتبطة بسند الصرف.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id',
            'organization_id'
        );
    }
}
