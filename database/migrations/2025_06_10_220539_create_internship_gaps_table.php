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
        Schema::create('internship_gaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_preparation_id')->constrained('document_preparations')->onDelete('cascade');
            $table->date('from_date'); // بداية الفجوة
            $table->date('to_date'); // نهاية الفجوة
            $table->integer('duration_days')->nullable(); // مدة الفجوة بالأيام (محسوبة تلقائياً)
            $table->text('reason'); // سبب الفجوة
            $table->text('additional_notes')->nullable(); // ملاحظات إضافية
            $table->timestamps();

            // Indexes
            $table->index('document_preparation_id');
            $table->index(['from_date', 'to_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_gaps');
    }
};
