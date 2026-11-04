<?php

namespace App\Models\FrontOrganizations\Owner;

use App\Models\admin\Agency\Agencymodel;
use App\Models\Admin\Organizations\Organization;
use App\Models\front\Owner\OwnerBankAccount;
use Illuminate\Database\Eloquent\Model;


class OwnerModels extends Model

{
   /**
     * اسم الجدول
     */
    protected $table = 'owners';

    /**
     * المفتاح الأساسي
     */
    protected $primaryKey = 'owner_id';

    /**
     * نوع المفتاح
     */
    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * الحقول القابلة للإدخال
     */
    protected $fillable = [
        'agency_id',//
        'full_name',// اسم المالك 
        'phone',// رقم المالك
        'phone2',// رقم المالك
        'email',// الايمال المالك
        'address',// العنوان
        'id_number', // رقم الهوية
        'id_type',// نوع الهويه
        'id_image',// صوره الهويه
        'nationality',// الجنس
        'bank_name',// اسم البنك
        'bank_account_name',// اسم صاحب الحساب 
        'bank_account_number',//رقم الحساب
        'bank_iban',//رقم البيان
        'preferred_payment_method',//طريقه الدفع
        'notes',//ملاحظات
        'is_active',//هل مفعل 
        'is_blacklisted'//// حالة القائمة السوداء للمالك
    ];

    /**
     * تحويل الأنواع
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_blacklisted'=>'boolean'
    ];

    /**
 * الحسابات البنكية للمالك
 */
// public function bankAccounts()
// {
//     return $this->hasMany(
//         OwnerBankAccount::class,
//         'owner_id',
//         'owner_id'
//     );
// }
    /**
     * علاقة المالك بالمؤسسة
     */
    public function agency()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'organization_id');
    }
      // علاقة المالك بالمباني
    // public function buildings()
    // {
    //     return $this->hasMany(
    //         \App\Models\admin\Building\BuildingModel::class,
    //         'owner_id'
    //     );
    // }


    // علاقة المالك بالعقود
    // public function contracts()
    // {
    //     return $this->hasMany(
    //         \App\Models\admin\Contract\ContractModel::class,
    //         'owner_id'
    //     );
    // }
}
