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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên chức vụ
            $table->string('code')->unique(); // Mã chức vụ
            $table->text('description')->nullable(); // Mô tả
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->decimal('salary_percentage', 5, 2)->default(0); // % lương từ hóa đơn
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
