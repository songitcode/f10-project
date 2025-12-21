<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();              // Mã voucher (VD: F10DISCOUNT20)
            $table->string('name');                        // Tên voucher (VD: Giảm 20%)
            $table->decimal('discount_percent', 5, 2)->default(0); // Giảm theo %
            $table->decimal('discount_amount', 12, 2)->default(0); // Giảm theo tiền
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
