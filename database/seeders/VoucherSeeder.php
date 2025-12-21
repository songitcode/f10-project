<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Illuminate\Support\Str;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🧾 Đang tạo dữ liệu mẫu cho bảng vouchers...');

        $vouchers = [
            [
                'code' => 'GIAM10',
                'name' => 'Giảm 10%',
                'discount_percent' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GIAM2000',
                'name' => 'Giảm 2.000$',
                'discount_amount' => 2000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($vouchers as $vc) {
            Voucher::create($vc);
        }

        $this->command->info('✅ Dữ liệu mẫu cho bảng vouchers đã được tạo thành công!');
    }
}
