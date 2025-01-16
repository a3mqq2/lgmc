<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctor_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->date('date');
            $table->unsignedBigInteger('pricing_id');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'rejected', 'under_process', 'done', 'canceled'])->default('pending');
            $table->enum('doctor_type', ['foreign', 'visitor', 'libyan','palestinian'])->nullable()->default('foreign');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');

            // Fields for rejected status
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamp('rejected_at')->nullable();

            // Fields for under_process status
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Fields for done status
            $table->unsignedBigInteger('done_by')->nullable();
            $table->timestamp('done_at')->nullable();

            // Fields for canceled status
            $table->timestamp('canceled_at')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('pricing_id')->references('id')->on('pricings')->onDelete('cascade');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('done_by')->references('id')->on('users')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_requests');
    }
}
