<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    //
    protected $fillable = ['name', 'code', 'description', 'role_id', 'salary_percentage'];

    // Quan hệ: Một position thuộc về một role
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // Quan hệ: Một position có nhiều users
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Accessor để lấy tên role
    public function getRoleNameAttribute()
    {
        return $this->role->name;
    }
}
