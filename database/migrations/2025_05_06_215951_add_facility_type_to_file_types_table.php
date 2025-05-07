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
        Schema::table('file_types', function (Blueprint $table) {
            $table->enum('facility_type', ['single','services'])->default('single')->nullable()->after('is_required')->comment('نوع المنشأة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_types', function (Blueprint $table) {
            $table->dropColumn('facility_type');
        });
    }
};
