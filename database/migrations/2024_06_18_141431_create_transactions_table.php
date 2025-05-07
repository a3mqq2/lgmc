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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vault_id')->constrained();
            $table->text('desc');
            $table->decimal('amount', 11,2);
            $table->decimal('balance', 11,2);
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('transaction_type_id')->nullable()->constrained();
            $table->enum('type', ['deposit', 'withdrawal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
