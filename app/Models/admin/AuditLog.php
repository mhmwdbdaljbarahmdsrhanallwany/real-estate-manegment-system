<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $primaryKey = 'audit_log_id';


    protected $fillable = [

        'action_id',

        'user_id',

        'action',

        'model_type',

        'model_id',

        'old_values',

        'new_values',

        'ip_address',

        'user_agent',

    ];


    protected $casts = [

        'old_values' => 'array',

        'new_values' => 'array',

    ];


    /**
     * الإجراء المرتبط بسجل التدقيق
     */
    public function action()
    {
        return $this->belongsTo(
            Action::class,
            'action_id',
            'action_id'
        );
    }


    /**
     * المستخدم الذي قام بالعملية
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }


    /**
     * الكيان الذي تم تنفيذ العملية عليه
     *
     * مثال:
     * Subscription
     * Invoice
     * Property
     */
    public function model()
    {
        return $this->morphTo();
    }

}