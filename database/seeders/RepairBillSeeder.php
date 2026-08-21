<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Voucher;
use App\Models\RepairBill;

class RepairBillSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_active', true)->with('position')->get();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ Không có user nào để gán hóa đơn. Hãy chạy UserSeeder trước.');
            return;
        }

        $vouchers = Voucher::where('is_active', true)->get();
        $this->command->info('🧾 Đang tạo dữ liệu mẫu cho bảng repair_bills...');

        foreach ($users as $user) {
            // Mỗi nhân viên tạo ngẫu nhiên 5–10 hóa đơn
            $billCount = rand(5, 10);

            for ($i = 0; $i < $billCount; $i++) {
                // Tổng tiền ngẫu nhiên (1.000–20.000)
                $total = rand(1000, 20000);

                // Chọn voucher ngẫu nhiên hoặc không áp dụng
                $voucher = $vouchers->random() ?? null;
                $discount = 0;

                // Tính mức giảm giá
                if ($voucher && method_exists($voucher, 'isValid') ? $voucher->isValid() : true) {
                    if ($voucher->discount_percent > 0) {
                        $discount = ($total * $voucher->discount_percent) / 100;
                    } elseif ($voucher->discount_amount > 0) {
                        $discount = $voucher->discount_amount;
                    }
                }

                // Tính số tiền cuối cùng & hoa hồng
                $final = max(0, $total - $discount);
                $percentage = 10;
                $earnings = round(($final * $percentage) / 100, 2);

                // Tạo hóa đơn
                RepairBill::create([
                    'bill_code' => 'HD' . strtoupper(Str::random(6)),
                    'user_id' => $user->id,
                    'customer_momo' => (string) rand(10000000, 99999999),
                    'vehicle_type' => collect(['Sedan', 'SUV', 'Moto', 'Truck', 'Bus'])->random(),
                    'services' => collect([
                        'Rửa xe',
                        'Thay nhớt',
                        'Sửa động cơ',
                        'Bảo dưỡng tổng thể',
                        'Thay phanh',
                        'Sơn lại',
                        'Vệ sinh nội thất',
                        'Căn chỉnh bánh xe'
                    ])->random(rand(2, 4))->implode(', '),
                    'total_amount' => $total,
                    'employee_earnings' => $earnings,
                    'percentage' => $percentage,
                    'voucher_id' => $voucher?->id,
                    'discount_amount' => $discount,
                    'final_amount' => $final,
                    'status' => collect(['pending', 'in_progress', 'completed', 'cancelled'])->random(),
                    'notes' => fake()->sentence(),
                    'created_at' => now()->subDays(rand(0, 60)),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Dữ liệu mẫu cho bảng repair_bills đã được tạo thành công!');
    }
}
