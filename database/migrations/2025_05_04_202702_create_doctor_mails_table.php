<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorMailsTable extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_mails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')
                  ->constrained('doctors')
                  ->onDelete('cascade');
            $table->boolean('contacted_before')->default(false);
            $table->enum('status', [
                'under_approve',
                'under_edit',
                'under_payment',
                'under_proccess',
                'done',
                'canceled'
            ])->default('under_approve');
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('work_mention', ['with', 'without'])->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('doctor_mails');
    }
    
}
