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
        Schema::table('medical_facilities', function (Blueprint $table) {
            if (!Schema::hasColumn('medical_facilities', 'index')) {
                $table->unsignedInteger('index')->nullable()->after('branch_id');
            }
            if (!Schema::hasColumn('medical_facilities', 'code')) {
                $table->string('code', 50)->nullable()->unique()->after('index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_facilities', function (Blueprint $table) {
            $table->dropColumn(['index', 'code']);
        });
    }
};
