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
        Schema::create('internship_training_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_preparation_id')->constrained('document_preparations')->onDelete('cascade');
            $table->string('specialty'); // التخصص
            $table->string('institution'); // المؤسسة
            $table->date('from_date'); // من تاريخ
            $table->date('to_date'); // إلى تاريخ
            $table->integer('duration_days')->nullable(); // مدة التدريب بالأيام (محسوبة تلقائياً)
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();

            // Indexes
            $table->index('document_preparation_id');
            $table->index(['from_date', 'to_date']);
            $table->index('specialty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_training_records');
    }
};
