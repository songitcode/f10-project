<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Role extends Model
{
    //
    protected $fillable = ['name', 'level', 'description'];

    // Quan hệ: Một role có nhiều positions
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    // Quan hệ: Một role có nhiều users (thông qua position)
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,      // model cuối
            Position::class,  // model trung gian
            'role_id',        // khóa ngoại trong bảng positions (trỏ đến roles.id)
            'position_id',    // khóa ngoại trong bảng users (trỏ đến positions.id)
            'id',             // khóa chính roles.id
            'id'              // khóa chính positions.id
        );
    }

    // Scope để lấy role theo level
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}
