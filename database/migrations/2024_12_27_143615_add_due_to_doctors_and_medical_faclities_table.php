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
        Schema::table('doctors', function (Blueprint $table) {
            $table->decimal('due', 10, 2)->default(0);
        });

        Schema::table('medical_facilities', function (Blueprint $table) {
            $table->decimal('due', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('due');
        });

        Schema::table('medical_facilities', function (Blueprint $table) {
            $table->dropColumn('due');
        });
    }
};
