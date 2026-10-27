<?php

namespace App\Models\Admin\Organizations;

use App\Models\AccessContext;
use App\Models\Admin\Subscriptions\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Organization extends Model
{
    use HasFactory, SoftDeletes;


    /**
     * اسم الجدول.
     */
    protected $table = 'organizations';

    /**
     * المفتاح الأساسي.
     */
    protected $primaryKey = 'organization_id';

    /**
     * الحقول القابلة للتعبئة الجماعية.
     */
    protected $fillable = [
        // بيانات المؤسسة
        'name',
        'commercial_name',
        'commercial_registration',
        'tax_number',
        'unified_national_number',

        // المستندات والتراخيص
        'municipality_license',
        'real_estate_broker_license',

        // بيانات التواصل
        'phone',
        'mobile',
        'email',
        'website',
        'logo',

        // العنوان والموقع
        'country',
        'state',
        'city',
        'district',
        'street',
        'address',
        'postal_code',
        'po_box',
        'latitude',
        'longitude',
        'map_url',

        // الممثل القانوني
        'legal_representative_name',
        'legal_representative_id',
        'legal_representative_phone',

        // البيانات البنكية
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_iban',

        // الحالة وإعدادات API
        'status',
        'api_key',
        'api_enabled',

        // ملاحظات
        'notes',
    ];

    

    /**
     * تحويل أنواع البيانات.
     */
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'api_enabled' => 'boolean',
        'deleted_at' => 'datetime',
    ];


    /**
     * حذف ملف من القرص العام.
     */
    public function deleteFile($value): void
    {
        if (empty($value)) {
            return;
        }

        $disk = Storage::disk('public');

        if ($disk->exists($value)) {
            $disk->delete($value);
        }
    }

    /**
     * حذف جميع ملفات المؤسسة المرتبطة بالتراخيص والشعار.
     */
    public function deleteFiles(): void
    {
        $fields = [
            'municipality_license',
            'real_estate_broker_license',
            'logo',
        ];

        foreach ($fields as $field) {
            $file = $this->{$field};

            if (!empty($file) && is_string($file)) {
                $this->deleteFile($file);
            }
        }
    }

   
    /**
     * إرجاع اسم حالة المؤسسة باللغة العربية.
     */
    public function getStatusLabelAttribute(): string
    {
        return [
            'pending_approval' => 'قيد الموافقة',
            'active' => 'نشطة',
            'suspended' => 'موقوفة',
            'blocked' => 'محظورة',
            'expired' => 'منتهية الاشتراك',
        ][$this->status] ?? '-';
    }


    /**
     * روابط التبويبات الخاصة بصفحة المؤسسة.
     */
    public function menuLinks(): array
    {
        return [
            [
                'title' => 'المستندات',
                'tab'   => 'documents',
            ],
            [
                'title' => 'المؤسسة',
                'tab'   => 'info',
            ],
            [
                'title' => 'الاشتراكات',
                'tab'   => 'subscriptions',
            ],
            [
                'title' => 'الباقات',
                'tab'   => 'packages',
            ],
        ];
    }

    /**
     * رابط صفحة عرض المؤسسة.
     */
    public function getShowRouteAttribute(): string
    {
        return route(
            'admin.organizations.show',
            $this->organization_id
        );
    }

    /**
     * اشتراكات المؤسسة.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(
            Subscription::class,
            'organization_id',
            'organization_id'
        );
    }

    /**
     * سياقات الوصول المرتبطة بالمؤسسة.
     */
    public function accessContexts(): HasMany
    {
        return $this->hasMany(
            AccessContext::class,
            'organization_id',
            'organization_id'
        );
    }
}