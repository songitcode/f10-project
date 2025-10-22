<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RepairBill;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', // Tên đăng nhập
        'real_name', // Tên thật
        'ingame_name', // Tên ingame
        'momo', // Số momo
        'birthday', // Ngày sinh
        'email', // Email
        'password', // Mật khẩu
        'start_date', // Ngày vào làm
        'position_id', // Chức vụ
        'is_active', // Trạng thái hoạt động
        'created_by', // Người tạo
        'deleted_by', // Người xóa
        'updated_by', // Người chỉnh sửa
        'deactivated_by', // Người vô hiệu hóa
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birthday' => 'date',
        'start_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Quan hệ: Một user thuộc về một position
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    // Quan hệ: Một user có nhiều repair bills
    public function repairBills(): HasMany
    {
        return $this->hasMany(RepairBill::class);
    }

    // Accessor để lấy role từ position
    public function getRoleAttribute()
    {
        return $this->position?->role;
    }

    // Accessor để lấy level từ role
    public function getRoleLevelAttribute()
    {
        return $this->role?->level;
    }

    // Tính tổng thu nhập từ tất cả hóa đơn
    public function getTotalEarningsAttribute()
    {
        return $this->repairBills()->sum('employee_earnings');
    }
    /*
    // Kiểm tra nếu user là admin (level 1) hoặc manager (level 1-4)
    */
    public function isAdmin()
    {
        return $this->getRoleLevelAttribute() === 1;
    }
    public function isQuanLyToanHeThong()
    {
        return $this->getRoleLevelAttribute() === 2;
    }
    public function isQuanLyNormal()
    {
        return $this->getRoleLevelAttribute() === 3;
    }
    public function isQuanLyHoaDon()
    {
        return $this->getRoleLevelAttribute() === 4;
    }
    public function isManager()
    {
        return $this->getRoleLevelAttribute() <= 4;
    }
    public function isQuanLyNhanSu()
    {
        return $this->getRoleLevelAttribute() <= 3;
    }
    // so sánh quyền của người hiện tại với người khác
    public function soSanhQuyen(User $otherUser): bool
    {
        return $this->getRoleLevelAttribute() < $otherUser->getRoleLevelAttribute();
    }

    public function getMapRoleLevel()
    {
        return match ($this->role?->level) {
            'admin' => 1,
            'quan_ly_toan_he_thong' => 2,
            'quan_ly_hoa_don' => 3,
            'quan_ly' => 4,
            'nhan_vien_thuong' => 5,
            default => 0,
        };
    }
    ////

    // Scope để lấy nhân viên đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Kiểm tra quyền dựa trên level
    public function hasPermission($requiredLevel)
    {
        return $this->roleLevel <= $requiredLevel;
    }

    // Mối quan hệ giữa người tạo, người xóa, người vô hiệu hóa với user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deactivator()
    {
        return $this->belongsTo(User::class, 'deactivated_by');
    }
    /**
     * Log hệ thống của người dùng (admin / quản lý)
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    /**
     * Log hoạt động của nhân viên
     */
    public function employeeLogs(): HasMany
    {
        return $this->hasMany(EmployeeLog::class, 'user_id');
    }
}
