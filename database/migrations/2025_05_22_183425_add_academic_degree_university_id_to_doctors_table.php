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
            $table->unsignedBigInteger('academic_degree_univeristy_id')->nullable();
            $table->foreign('academic_degree_univeristy_id')
                  ->references('id')
                  ->on('universities')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['academic_degree_univeristy_id']);
            $table->dropColumn('academic_degree_univeristy_id');
        });
    }
};
