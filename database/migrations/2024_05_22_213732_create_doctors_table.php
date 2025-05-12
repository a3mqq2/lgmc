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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('name_en');
            $table->string('national_number');
            $table->string('mother_name');
            $table->foreignId('country_id')->nullable()->constrained();
            $table->string('date_of_birth');
            $table->enum('marital_status', ['married', 'single']);
            $table->enum('gender', ['male', 'female']);
            $table->string('passport_number');
            $table->dateTime('passport_expiration');
            $table->string('address');
            $table->string('phone');
            $table->string('phone_2')->nullable();
            $table->string('email')->unique();
            $table->unsignedBigInteger('hand_graduation_id');
            $table->foreign('hand_graduation_id')->references('id')->on('universities')->onDelete('cascade');
            $table->dateTime('internership_complete');
            $table->foreignId('academic_degree_id')->nullable()->constrained();
            $table->unsignedBigInteger('qualification_university_id');
            $table->foreign('qualification_university_id')->references('id')->on('universities')->onDelete('cascade');
            $table->dateTime('certificate_of_excellence_date');
            $table->foreignId('doctor_rank_id')->constrained();
            $table->text('ex_medical_facilities')->nullable();
            $table->integer('experience')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('specialty_1_id')->nullable();
            $table->unsignedBigInteger('specialty_2_id')->nullable();
            $table->unsignedBigInteger('specialty_3_id')->nullable();
            $table->foreign('specialty_1_id')->references('id')->on('specialties')->nullOnDelete();
            $table->foreign('specialty_2_id')->references('id')->on('specialties')->nullOnDelete();
            $table->enum('membership_status', [
                'under_approve',
                'under_edit',
                'under_payment',
                'active',
                'expired',
                'banned',
            ])->default('under_approve');

            $table->date('membership_expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
