<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('ownership', ['private', 'public']);
            $table->unsignedBigInteger('medical_facility_type_id');
            $table->date('activity_start_date')->nullable();
            $table->foreign('medical_facility_type_id')->references('id')->on('medical_facility_types')->onDelete('cascade');
            $table->string('address');
            $table->string('phone_number');
            $table->enum('membership_status', ['active', 'inactive']);
            $table->date('membership_expiration_date')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('serial_number')->nullable();
            $table->enum('activity_type', ['commercial_record', 'negative_certificate']);
            $table->foreignId('branch_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_facilities');
    }
}
