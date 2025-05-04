<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('doctor_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('doctor_email_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_email_id')->constrained('doctor_emails')->cascadeOnDelete();
            $table->foreignId('pricing_id')->constrained()->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->enum('status',['pending','under_process','rejected','done'])->default('pending');
            $table->string('reason')->nullable();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_email_requests');
        Schema::dropIfExists('doctor_emails');
    }
};
