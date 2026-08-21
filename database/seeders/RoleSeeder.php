<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = [
            [
                'name' => 'Admin F10',
                'level' => 1,
                'description' => 'Đầy đủ các quyền'
            ],
            [
                'name' => 'Quản lý toàn hệ thống F10',
                'level' => 2,
                'description' => 'Quản lý toàn hệ thống F10 nhưng hạn chế một số quyền với ADMIN'
            ],
            [
                'name' => 'Quản lý',
                'level' => 3,
                'description' => 'Quản lý cao hơn một cấp, và chung về nhân sự, hóa đơn'
            ],
            [
                'name' => 'Quản lý hóa đơn',
                'level' => 4,
                'description' => 'Quản lý tiếp quản phần hóa đơn nhân viên, không đụng chạm đến nhân sự'
            ],
            [
                'name' => 'Nhân viên bình thường',
                'level' => 5,
                'description' => 'Nhân viên bình thường, sử dụng các tính năng ở giao diện của nhân viên'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
        $this->command->info('✅ Đã tạo dữ liệu mẫu cho bảng roles thành công!');
    }
}
