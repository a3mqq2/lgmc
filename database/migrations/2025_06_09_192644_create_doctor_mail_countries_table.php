<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorMailCountriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_mail_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_mail_id')->constrained()->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('country_value');
            $table->boolean('is_new')->default(false);
            $table->timestamps();
            
            $table->index('doctor_mail_id');
            $table->index('country_id');
            $table->index('is_new');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_mail_countries');
    }
}