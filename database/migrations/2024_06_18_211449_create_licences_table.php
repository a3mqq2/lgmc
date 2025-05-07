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
        Schema::create('licences', function (Blueprint $table) {
            $table->id();
            $table->morphs('licensable');
            $table->date('issued_date');
            $table->date('expiry_date');
            $table->enum('status', ['active', 'expired', 'revoked', 'under_payment', 'under_approve_admin', 'under_approve_branch']);
            $table->foreignId('doctor_id')->nullable()->constrained();
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('revoked_at')->nullable();
            $table->decimal('amount',11,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licences');
    }
};
