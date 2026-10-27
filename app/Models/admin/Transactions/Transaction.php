<?php

namespace App\Models\Admin\Transactions;

use App\Models\Admin\Cashboxes\Cashbox;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'cashboxe_id',
        'type',
        'amount',
        'reference_type',
        'reference_id',
        'balance_after',
        'currency',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * الصندوق المرتبط بالحركة.
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
     * المستخدم الذي سجّل الحركة.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}
