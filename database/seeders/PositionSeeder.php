<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Role;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $positions = [
            // Level 1 
            [
                'name' => 'Chủ Tịch',
                'code' => 'chu_tich',
                'role_id' => 1,
                'salary_percentage' => 0.00,
                'description' => 'Quản lý toàn bộ công ty'
            ],
            // Level 2 
            [
                'name' => 'Giám đốc',
                'code' => 'giam_doc',
                'role_id' => 2,
                'salary_percentage' => 0.00,
                'description' => 'Quản lý toàn bộ hệ thống F10'
            ],
            // Level 3 
            [
                'name' => 'Quản lý (thường)',
                'code' => 'quan_ly',
                'role_id' => 3,
                'salary_percentage' => 0.00,
                'description' => 'Quản lý chung nhưng hạn chế'
            ],
            // Level 4 
            [
                'name' => 'Quản lý hóa đơn',
                'code' => 'quan_ly_hoa_don',
                'role_id' => 4,
                'salary_percentage' => 0.00,
                'description' => 'Quản lý hóa đơn chung không đụng tới nhân sự'
            ],
            // Level 5 
            [
                'name' => 'Nhân viên chính thức',
                'code' => 'nhan_vien_chinh_thuc',
                'role_id' => 5,
                'salary_percentage' => 0.00,
                'description' => 'Nhân viên chính thức'
            ],
            [
                'name' => 'Nhân viên thực tập',
                'code' => 'nhan_vien_thuc_tap',
                'role_id' => 5,
                'salary_percentage' => 0.00,
                'description' => 'Nhân viên thực tập'
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
        $this->command->info('✅ Đã tạo dữ liệu mẫu cho bảng positions thành công!');
    }
}
