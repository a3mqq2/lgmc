<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorMailEmailsTable extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_mail_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_mail_id')->constrained()->onDelete('cascade');
            $table->foreignId('email_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('email_value');
            $table->boolean('is_new')->default(false);
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->timestamps();
            
            $table->index('doctor_mail_id');
            $table->index('email_id');
            $table->index('is_new');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_mail_emails');
    }
}