<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascadeDeleteLicenceLogs extends Migration
{
    public function up()
    {
        Schema::table('licence_logs', function (Blueprint $table) {
            $table->dropForeign(['licence_id']);
            $table->foreign('licence_id')
                  ->references('id')
                  ->on('licences')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('licence_logs', function (Blueprint $table) {
            $table->dropForeign(['licence_id']);
            $table->foreign('licence_id')
                  ->references('id')
                  ->on('licences');
        });
    }
}
