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
        Schema::create('blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number_phone');
            $table->string('passport_number')->nullable();
            $table->string('id_number')->nullable();
            $table->string('reason')->nullable();
            $table->enum('doctor_type', ['foreign', 'visitor', 'libyan','palestinian'])->nullable()->default('foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blacklists');
    }
};
