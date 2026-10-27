<?php

namespace App\Models\Admin;

use App\Models\Admin\Organizations\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;


    protected $table = 'actions';


    protected $primaryKey = 'action_id';



    protected $fillable = [
        'action_type_id',
        'organization_id',
        'user_id',
        'subject_type',
        'subject_id',
        'title',
        'description',
        'old_values',
        'new_values',
        'metadata',
        'ip_address',
        'user_agent',
        'event_key',
    ];



    protected $casts = [

        'old_values'=>'array',

        'new_values'=>'array',

        'metadata'=>'array',

        'performed_at'=>'datetime',

    ];




    /**
     * نوع الإجراء
     */
    public function actionType()
    {
        return $this->belongsTo(
            ActionType::class,
            'action_type_id',
            'action_type_id'
        );
    }




    /**
     * المؤسسة المرتبطة بالإجراء
     */
    public function organization()
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id',
            'organization_id'
        );
    }




    /**
     * العنصر المرتبط بالإجراء
     *
     * اشتراك / فاتورة / عقار ...
     */
    public function subject()
    {
        return $this->morphTo();
    }




    /**
     * المستخدم المنفذ للإجراء
     *
     * سيتم تفعيل العلاقة بعد تجهيز جدول المستخدمين
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }


}