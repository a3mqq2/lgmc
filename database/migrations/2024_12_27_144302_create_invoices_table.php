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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->morphs('invoiceable'); 
            $table->string('description');
            $table->foreignId('licence_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pricing_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['paid', 'unpaid','relief'])->default('unpaid');
            $table->date('received_at')->nullable();
            $table->date('relief_at')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->foreign('received_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('relief_by')->nullable();
            $table->foreign('relief_by')->references('id')->on('users')->nullOnDelete();
            $table->text('relief_reason')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
