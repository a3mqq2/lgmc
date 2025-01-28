<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Modify the foreign key constraint on the `vault_id` column in the `branches` table
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['vault_id']);  // Drop the existing foreign key
            $table->foreign('vault_id')->references('id')->on('vaults')->onDelete('set null');  // Set to null on delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Rollback: Restore the original foreign key (with cascade or original settings)
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['vault_id']);
            $table->foreign('vault_id')->references('id')->on('vaults')->onDelete('cascade');
        });
    }
};
