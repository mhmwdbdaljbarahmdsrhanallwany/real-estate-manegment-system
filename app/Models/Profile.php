<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'alternate_phone',
        'avatar',
        'gender',
        'birth_date',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date:Y-m-d',
    ];
    /**
     * العلاقة مع جدول المستخدمين
     * كل Profile يتبع User واحد
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function getFullNameAttribute(): string
{
    return "{$this->first_name} {$this->last_name}";
}
  
}