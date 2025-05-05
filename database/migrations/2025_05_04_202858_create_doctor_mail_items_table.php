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
        Schema::create('doctor_mail_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_mail_id')->constrained('doctor_mails')->onDelete('cascade');
            $table->foreignId('pricing_id')->constrained('pricings')->onDelete('cascade');
            $table->enum('status', ['pending','rejected','approved'])->default('pending');
            $table->text('rejected_reason')->nullable();
            $table->text('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_mail_items');
    }
};
