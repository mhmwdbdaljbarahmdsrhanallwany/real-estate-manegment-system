<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ActionType extends Model
{
    use HasFactory;

    protected $table = 'action_types';

    protected $primaryKey = 'action_type_id';


    protected $fillable = [

        'name',
        'code',
        'category',
        'icon',
        'color',
        'level',
        'send_notification',
        'write_audit_log',
        'is_active',
        'description',
        'sort_order',
        'is_system',

    ];



    /**
     * الإجراءات التابعة لهذا النوع
     */
    public function actions()
    {
        return $this->hasMany(
            Action::class,
            'action_type_id',
            'action_type_id'
        );
    }



    /**
     * اسم المستوى بالعربية
     */
    public function getLevelLabelAttribute()
    {
        return [

            'info' => 'معلومات',

            'success' => 'نجاح',

            'warning' => 'تحذير',

            'danger' => 'خطر',

        ][$this->level] ?? '-';
    }
    protected $casts = [

        'send_notification' => 'boolean',
        'write_audit_log'   => 'boolean',
        'is_active'         => 'boolean',
        'is_system'         => 'boolean',
    
    ];

    public function lastAction()
    {
        return $this->hasOne(
            Action::class,
            'action_type_id',
            'action_type_id'
        )->latest('created_at');
    }

}