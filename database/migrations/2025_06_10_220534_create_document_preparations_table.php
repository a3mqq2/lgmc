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
        Schema::create('document_preparations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_mail_id')->constrained('doctor_mails')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('doctor_mail_services')->onDelete('cascade');
            $table->string('document_type'); // good_standing, specialist, license, certificate, internship_second_year
            $table->json('preparation_data')->nullable(); // لحفظ البيانات الإضافية
            $table->enum('status', ['pending', 'prepared', 'completed'])->default('pending');
            $table->string('document_path')->nullable(); // مسار الملف المُنشأ
            $table->timestamp('prepared_at')->nullable();
            $table->foreignId('prepared_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['doctor_mail_id', 'service_id']);
            $table->index('document_type');
            $table->index('status');
            $table->unique(['doctor_mail_id', 'service_id']); // منع التكرار
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_preparations');
    }
};
