<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionModule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(
            Permission::class,
            'module_id'
        );
    }
}
