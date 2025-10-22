<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Position;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = Position::all();

        if ($positions->isEmpty()) {
            $this->command->warn('⚠️ Không có chức vụ nào trong hệ thống. Hãy chạy PositionSeeder trước.');
            return;
        }

        $this->command->info('👷‍♂️ Bắt đầu tạo dữ liệu nhân viên...');

        // Tạo danh sách 10 nhân viên mẫu
        for ($i = 1; $i <= 10; $i++) {
            $realName = fake()->name();
            $ingameName = 'Game_' . strtoupper(Str::random(5));
            $email = 'employee' . $i . '@example.com';

            User::create([
                'name' => 'employee' . $i,
                'real_name' => $realName,
                'ingame_name' => $ingameName,
                'momo' => '09' . rand(10000000, 99999999),
                'birthday' => Carbon::now()->subYears(rand(20, 35))->subDays(rand(1, 365)),
                'start_date' => Carbon::now()->subDays(rand(5, 90)),
                'position_id' => $positions->random()->id,
                'is_active' => true,
                'created_by' => 1, // ID admin tạo
                'updated_by' => null,
                'deleted_by' => null,
                'deactivated_by' => null,
                'email' => $email,
                'password' => Hash::make('123456'), // mật khẩu mặc định
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Đã tạo 10 nhân viên mẫu thành công!');
    }
}
