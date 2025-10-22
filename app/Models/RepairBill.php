<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepairBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_code',
        'user_id',
        'customer_momo',
        'vehicle_type',
        'license_plate',
        'services',
        'total_amount',
        'employee_earnings',
        'status',
        'notes',
    ];

    /**
     * Relationships
     */
    protected $casts = [
        'services' => 'array',
    ];

    // Một hóa đơn chỉ thuộc về một user (nhân viên tạo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
