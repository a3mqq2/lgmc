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
        Schema::table('doctor_emails', function (Blueprint $table) {
            $table->boolean('has_docs')->default(false);
            $table->unsignedSmallInteger('last_year')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_emails', function (Blueprint $table) {
            $table->dropColumn(['has_docs', 'last_year']);
        });
    }
};
