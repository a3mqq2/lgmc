<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorRankIdAndDoctorTypeToFileTypesTable extends Migration
{
    public function up()
    {
        Schema::table('file_types', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_rank_id')->nullable();
            $table->enum('doctor_type', ['foreign', 'visitor', 'libyan','palestinian'])->nullable()->default('foreign');
        });
    }

    public function down()
    {
        Schema::table('file_types', function (Blueprint $table) {
            $table->dropColumn(['doctor_rank_id', 'doctor_type']);
        });
    }
}
