<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDoctorColumnsNullable extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->foreignId('doctor_rank_id')->nullable()->change();
            $table->foreignId('specialty_1_id')->nullable()->change();
            $table->date('certificate_of_excellence_date')->nullable()->change();
            $table->date('date_of_birth')->nullable()->change();
            $table->timestamp('created_at')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('phone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->foreignId('doctor_rank_id')->nullable(false)->change();
            $table->foreignId('specialty_1_id')->nullable(false)->change();
            $table->date('certificate_of_excellence_date')->nullable(false)->change();
            $table->date('date_of_birth')->nullable(false)->change();
            $table->timestamp('created_at')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
        });
    }
}
