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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('vault_transfer_id')
                ->nullable()
                ->after('financial_category_id')
                ->comment('ID of the transfer in the vault system');
            // Optionally, you can add an index for faster lookups
            $table->index('vault_transfer_id', 'idx_vault_transfer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_vault_transfer_id'); // Drop the index if it exists
            $table->dropColumn('vault_transfer_id'); // Drop the column
        });
    }
};
