<?php
// database/migrations/2025_04_24_123000_update_licences_fk_cascade.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licences', function (Blueprint $table) {
            // احذف المفتاح الحالي
            $table->dropForeign(['doctor_id']);

            // أعد إنشاؤه مع Cascade
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('doctors')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('licences', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);

            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('doctors');
        });
    }
};
