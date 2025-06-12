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
            $table->string('code')->nullable();
            $table->string('index')->nullable();
            $table->string('type');
            $table->string('address');
            $table->string('commercial_number')->nullable();
            $table->string('phone_number');
            $table->enum('membership_status', ['under_approve','under_complete','under_payment','under_edit','active', 'expired', 'banned','under_renew']);
            $table->date('membership_expiration_date')->nullable();
            $table->string('edit_reason')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained();
            $table->integer('manager_id');
            $table->integer('renew_number')->nullable();
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
