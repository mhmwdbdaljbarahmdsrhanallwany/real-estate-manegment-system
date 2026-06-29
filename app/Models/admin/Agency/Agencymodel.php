<?php

namespace App\Models\admin\Agency;

use App\Models\admin\AgencySubscription\AgencySubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Agencymodel extends Model
{
    use HasFactory;

    protected $table = 'agency';
   
    protected $primaryKey = 'agency_id';

    protected $fillable = [
        'agency_name',
        'commercial_registration',
        'tax_number',
        'municipality_license',
        'real_estate_broker_license',
        'unified_national_number',
        'phone',
        'mobile',
        'email',
        'website',
        'address',
        'city',
        'district',
        'postal_code',
        'po_box',
        'legal_representative_name',
        'legal_representative_id',
        'legal_representative_phone',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_iban',
        'status',
        'subscription_expiry',
        'subscription_status',
        'trial_expiry',
        'access_blocked',
        'block_reason',
        'last_payment_check',
        'max_branches',
        'max_users',
        'max_units',
        'api_key',
        'api_enabled',
        'grace_period_days',
        'auto_suspend',
        'documents_path',
        'verification_date',
        'verified_by',
        'rejection_reason',
    ];

    protected $casts = [
        'municipality_license' => 'array',
        'real_estate_broker_license' => 'array',
        'documents_path' => 'array',
        'access_blocked' => 'boolean',
        'api_enabled' => 'boolean',
        'auto_suspend' => 'boolean',
    ];

    public function delete_File($value)
{
    if (empty($value)) {
        return;
    }

    // القيمة هنا مصفوفة مباشرة بفضل الـ casts
    foreach ($value as $file) {
        if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
            Storage::disk('public')->delete($file['path']);
        }
    }
}

    

    public function deleteFiles()
    {
        $fields = [
            'municipality_license',
            'real_estate_broker_license',
            'documents_path',
        ];
    
        foreach ($fields as $field) {
            $files = $this->$field; // مصفوفة مباشرة بفضل casts
    
            if (!empty($files) && is_array($files)) {
                foreach ($files as $file) {
                    if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                }
                // بعد الحذف، نظف الحقل في قاعدة البيانات
                
            }
        }
    
       
    }
    

    // public function getMunicipalityUrlAttribute()
    // {
    //     if ($this->municipality_license) {
    //         foreach ($this->municipality_license as $index => $doc) {
    //             //  return asset('storage/' .$doc['path']);
    //             return Storage::disk('public')->path($doc['path']);
    //         }
    //     } else {
    //         return asset('assets/agency/defulte_decument.png');
    //     }
    //     // <img src="{{ $data->municipality_url  }}" target="_blank">
    // }

    // دالة ترجمة الحالة
    public function getStatusLabelAttribute()
    {
        $map = [
            'pending_approval' => 'قيد الموافقة',
            'active' => 'نشط',
            'suspended' => 'موقوف',
            'blocked' => 'محظور',
            'expired' => 'منتهي الاشتراك',
        ];

        return $map[$this->status] ?? '-';
    }

    public function getSubscriptionStatusLabelAttribute()
    {
        $statuses = [
            'trial' => 'تجريبي',
            'active' => 'نشطة',
            'expired' => 'منتهية',
            'grace_period' => 'فترة سماح',
        ];

        return $statuses[$this->attributes['subscription_status']] ?? $this->attributes['subscription_status'];
    }

    public function subscriptions()
    {
        return $this->hasMany(AgencySubscription::class, 'agency_id');
    }
}
