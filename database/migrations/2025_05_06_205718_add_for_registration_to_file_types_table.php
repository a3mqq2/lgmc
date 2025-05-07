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
            $table->boolean('for_registration')->default(false)->after('is_required')->comment('هل هو مستند مطلوب للتسجيل');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_types', function (Blueprint $table) {
            $table->dropColumn('for_registration');
        });
    }
};
