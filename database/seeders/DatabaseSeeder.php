<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Voucher;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PositionSeeder::class,
            VoucherSeeder::class,
            AdminUserSeeder::class,
            EmployeeSeeder::class,
            RepairBillSeeder::class,
        ]);
    }
}
