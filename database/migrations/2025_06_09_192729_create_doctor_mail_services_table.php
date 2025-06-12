<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorMailServicesTable extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_mail_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_mail_id')->constrained()->onDelete('cascade');
            $table->foreignId('pricing_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->enum('work_mention', ['with', 'without'])->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();
            
            $table->index('doctor_mail_id');
            $table->index('pricing_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_mail_services');
    }
}