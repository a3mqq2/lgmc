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
            $table->unsignedBigInteger('branch_id');
            $table->foreign('medical_facility_type_id')->references('id')->on('medical_facility_types')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->string('address');
            $table->string('phone_number');
            $table->foreignId('user_id')->constrained();
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
