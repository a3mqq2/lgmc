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
        Schema::create('licences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('cascade');
            $table->foreignId('medical_facility_id')->nullable()->constrained('medical_facilities')->onDelete('cascade');
            $table->integer('index')->nullable();
            $table->string('code', 50)->nullable();
            $table->date('issued_date');
            $table->date('expiry_date');
            $table->string('status');
            $table->string('doctor_type')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('amount',11,2)->nullable();
            $table->foreignId('doctor_rank_id')->nullable()->constrained('doctor_ranks')->onDelete('cascade');
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->onDelete('cascade');
            $table->unsignedBigInteger('workin_medical_facility_id')->nullable();
            $table->foreign('workin_medical_facility_id')->references('id')->on('medical_facilities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licences');
    }
};
