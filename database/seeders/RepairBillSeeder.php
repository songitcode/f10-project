<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\RepairBill;
use App\Models\User;

class RepairBillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách user đang hoạt động
        $users = User::where('is_active', true)->get();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ Không có user nào để gán hóa đơn sửa chữa. Hãy chạy AdminUserSeeder hoặc thêm user trước.');
            return;
        }

        $this->command->info('🧾 Bắt đầu tạo hóa đơn sửa chữa (repair_bills)...');

        foreach ($users as $user) {
            // Mỗi user tạo 3–5 hóa đơn mẫu
            $count = rand(10, 20);
            $soLuong = 50;
            for ($i = 1; $i <= $soLuong; $i++) {
                RepairBill::create([
                    'bill_code' => 'HD' . strtoupper(Str::random(6)),
                    'user_id' => $user->id,
                    'customer_momo' => rand(10000, 99999),
                    'vehicle_type' => collect(['Sedan', 'Bus', 'SUV', 'Moto'])->random(),
                    'license_plate' => strtoupper('F10-' . rand(100, 999)),
                    'services' => 'Trục chuyển động, Động cơ, Thân vỏ, Các bộ phận khác, Phí lưu động, Rửa xe, Bão dưỡng máy bay trọn gói',
                    'total_amount' => rand(50000, 500000),
                    'employee_earnings' => rand(10000, 80000),
                    'percentage' => rand(5, 20),
                    'status' => collect(['pending', 'completed', 'cancelled'])->random(),
                    'notes' => fake()->sentence(),
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Đã tạo dữ liệu mẫu cho bảng repair_bills thành công!');
    }
}
