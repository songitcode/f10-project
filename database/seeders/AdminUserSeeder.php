<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Position;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // 1. Lấy Role "Admin F10"
        $role = Role::where('name', 'Admin F10')->first();

        if (!$role) {
            $this->command->error('Role "Admin F10" chưa tồn tại. Hãy chạy RoleSeeder trước.');
            return;
        }

        // 2. Tạo Position cho Admin nếu chưa có
        $position = Position::firstOrCreate(
            ['code' => 'admin_f10'],
            [
                'name' => 'Administrator F10',
                'description' => 'Chức vụ cao nhất, toàn quyền hệ thống',
                'role_id' => $role->id,
                'salary_percentage' => 0
            ]
        );

        // 3. Tạo User Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'real_name' => 'Admin',
                'ingame_name' => 'AdminGame',
                'momo' => '531548',
                'birthday' => '1990-01-01',
                'password' => Hash::make('admin'), //admin@#qazxsw
                'start_date' => now(),
                'position_id' => $position->id,
                'is_active' => true,
                'created_by' => null, // Admin đầu tiên tự sinh ra, không có người tạo
                'updated_by' => null,
                'deleted_by' => null,
                'deactivated_by' => null,
            ]
        );
    }
}
