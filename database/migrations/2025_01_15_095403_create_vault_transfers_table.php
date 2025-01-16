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
        Schema::create('vault_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_vault_id')
                  ->constrained('vaults')
                  ->onDelete('cascade');
            $table->foreignId('to_vault_id')
                  ->constrained('vaults')
                  ->onDelete('cascade');
            $table->text('description')->nullable();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('branch_id')
                    ->nullable()
                    ->constrained('branches')
                    ->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->foreignId('rejected_by')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('set null');
            $table->dateTime('rejected_at')->nullable();
            $table->foreignId('from_transaction_id')
                  ->nullable()
                  ->constrained('transactions')
                  ->onDelete('set null');
                  $table->foreignId('to_transaction_id')
                  ->nullable()
                  ->constrained('transactions')
                  ->onDelete('set null');

            $table->decimal('amount',8,2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vault_transfers');
    }
};
