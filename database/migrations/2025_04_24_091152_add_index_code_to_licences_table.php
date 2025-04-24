<?php
// database/migrations/2025_04_24_130000_add_index_code_to_licences_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licences', function (Blueprint $table) {
            if (!Schema::hasColumn('licences', 'index')) {
                $table->unsignedInteger('index')->nullable()->after('branch_id');
            }
            if (!Schema::hasColumn('licences', 'code')) {
                $table->string('code', 50)->nullable()->unique()->after('index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('licences', function (Blueprint $table) {
            $table->dropColumn(['index', 'code']);
        });
    }
};
