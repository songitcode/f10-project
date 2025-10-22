<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repair_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_code')->unique(); // Mã hóa đơn HDxxxxx
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Nhân viên tạo
            $table->string('customer_momo'); // Momo khách hàng
            $table->string('vehicle_type'); // Loại xe
            $table->string('license_plate')->nullable(); // Biển số
            $table->text('services'); // Dịch vụ sửa chữa
            $table->decimal('total_amount', 12, 2); // Tổng tiền
            $table->decimal('employee_earnings', 12, 2); // Tiền nhân viên nhận
            $table->string('status')->default('pending'); // Trạng thái
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_bills');
    }
};
