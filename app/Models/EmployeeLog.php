<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLog extends Model
{
    protected $fillable = ['user_id', 'action', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    // public function employee(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
