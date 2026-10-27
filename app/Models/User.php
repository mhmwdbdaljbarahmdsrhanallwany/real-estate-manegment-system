<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $appends = [
        'full_name',
    ];
    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    /**
     * الحقول المخفية
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getFullNameAttribute()
{
    if (!$this->profile) {
        return $this->name;
    }

    return trim(
        $this->profile->first_name . ' ' .
        $this->profile->last_name
    );
}




    /**
     * التحويلات
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * الملف الشخصي للمستخدم
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }


    /**
     * وسائل تسجيل الدخول
     */
    public function authenticationMethods(): HasMany
    {
        return $this->hasMany(AuthenticationMethod::class);
    }


    /**
     * تعيينات المستخدم
     *
     * يحدد أين يعمل المستخدم
     * (النظام / المؤسسة / الفرع)
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(UserAssignment::class);
    }


    /**
     * أدوار المستخدم عبر التعيينات
     *
     * مثال:
     * نفس المستخدم قد يكون مدير مؤسسة
     * وموظف في فرع آخر
     */
  


    /**
     * الملفات الشخصية التي أنشأها
     */
    public function createdProfiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'created_by');
    }


    /**
     * الملفات الشخصية التي عدلها
     */
    public function updatedProfiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'updated_by');
    }


    /**
     * تعيينات المستخدم التي أنشأها
     */
    public function createdAssignments(): HasMany
    {
        return $this->hasMany(UserAssignment::class, 'created_by');
    }


    /**
     * تعيينات المستخدم التي عدلها
     */
    public function updatedAssignments(): HasMany
    {
        return $this->hasMany(UserAssignment::class, 'updated_by');
    }

/**
 * روابط التبويبات الخاصة بصفحة المستخدم.
 */
public function menuLinks(): array
{
    return [
        [
            'title' => 'معلومات الحساب',
            'tab'   => 'info',
        ],
        [
            'title' => 'سياقات الوصول',
            'tab'   => 'contexts',
        ],
        [
            'title' => 'الأدوار المسندة',
            'tab'   => 'roles',
        ],
    ];
}

/**
 * رابط صفحة عرض المستخدم.
 */
public function getShowRouteAttribute(): string
{
    return route('admin.users.show', $this->id);
}
}