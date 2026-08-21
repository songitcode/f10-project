<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'discount_percent',
        'discount_amount',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function repairBills()
    {
        return $this->hasMany(RepairBill::class);
    }
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function isValid()
    {
        $today = now();
        return $this->is_active &&
            (!$this->start_date || $today >= $this->start_date) &&
            (!$this->end_date || $today <= $this->end_date);
    }
}
