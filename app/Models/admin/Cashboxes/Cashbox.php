<?php

namespace App\Models\Admin\Cashboxes;

use App\Models\Admin\ReceiptVouchers\ReceiptVoucher;
use App\Models\Admin\PaymentVouchers\PaymentVoucher;
use App\Models\Admin\Transactions\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cashbox extends Model
{
    protected $table = 'cashboxes';

    protected $primaryKey = 'cashboxe_id';

    protected $fillable = [
        'name',
        'is_main',
        'code',
        'opening_balance',
        'current_balance',
        'currency',
        'status',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    /**
     * سندات القبض الخاصة بالصندوق.
     */
    public function receiptVouchers(): HasMany
    {
        return $this->hasMany(
            ReceiptVoucher::class,
            'cashboxe_id',
            'cashboxe_id'
        );
    }

    /**
     * سندات الصرف الخاصة بالصندوق.
     */
    public function paymentVouchers(): HasMany
    {
        return $this->hasMany(
            PaymentVoucher::class,
            'cashboxe_id',
            'cashboxe_id'
        );
    }

    /**
     * الحركات المالية الخاصة بالصندوق.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(
            Transaction::class,
            'cashboxe_id',
            'cashboxe_id'
        );
    }
}
