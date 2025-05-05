<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorMailIdToInvoices extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_mail_id')->nullable()->after('id');
            $table->foreign('doctor_mail_id')
                  ->references('id')->on('doctor_mails')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['doctor_mail_id']);
            $table->dropColumn('doctor_mail_id');
        });
    }
}
