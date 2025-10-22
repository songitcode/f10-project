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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('real_name')->after('name'); // Tên thật
            $table->string('ingame_name')->after('real_name'); // Tên ingame
            $table->string('momo')->after('ingame_name'); // Số momo
            $table->date('birthday')->nullable()->after('momo'); // Ngày sinh
            $table->date('start_date')->after('birthday'); // Ngày vào làm
            // Chức vụ
            $table->foreignId('position_id')
                ->nullable()
                ->after('start_date')
                ->constrained()
                ->onDelete('set null');
            // Trạng thái hoạt động
            $table->boolean('is_active')->default(true)->after('position_id');

            // Theo dõi người thao tác
            $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->after('deleted_by');
            $table->unsignedBigInteger('deactivated_by')->nullable()->after('updated_by');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {

            $foreignKeys = ['position_id', 'created_by', 'deleted_by', 'deactivated_by'];
            foreach ($foreignKeys as $fk) {
                if (Schema::hasColumn('users', $fk)) {
                    try {
                        Schema::table('users', function (Blueprint $table) use ($fk) {
                            $table->dropForeign([$fk]);
                        });
                    } catch (\Exception $e) {
                        // Có thể log lỗi nếu cần
                    }
                }
            }

            $dropColumns = [
                'real_name',
                'ingame_name',
                'momo',
                'birthday',
                'start_date',
                'position_id',
                'is_active',
                'created_by',
                'deleted_by',
                'deactivated_by'
            ];

            foreach ($dropColumns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    Schema::table('users', function (Blueprint $table) use ($col) {
                        $table->dropColumn($col);
                    });
                }
            }
        }
    }
};
