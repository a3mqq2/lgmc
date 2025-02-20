<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('mother_name')->nullable()->change();
            $table->string('passport_number')->nullable()->change();
            $table->date('passport_expiration')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->foreignId('hand_graduation_id')->nullable()->change();
            $table->foreignId('internership_complete')->nullable()->change();
            $table->foreignId('qualification_university_id')->nullable()->change();
            $table->foreignId('branch_id')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('mother_name')->nullable(false)->change();
            $table->string('passport_number')->nullable(false)->change();
            $table->date('passport_expiration')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->foreignId('hand_graduation_id')->nullable(false)->change();
            $table->foreignId('internership_complete')->nullable(false)->change();
            $table->foreignId('qualification_university_id')->nullable(false)->change();
            $table->foreignId('branch_id')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
        });
    }
};
