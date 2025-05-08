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
        Schema::table('pricings', function (Blueprint $table) {
            $table->foreignId('doctor_rank_id')
                ->nullable()
                ->constrained('doctor_ranks')
                ->nullOnDelete()
                ->after('doctor_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricings', function (Blueprint $table) {
            $table->dropForeign(['doctor_rank_id']);
            $table->dropColumn('doctor_rank_id');
        });
    }
};
