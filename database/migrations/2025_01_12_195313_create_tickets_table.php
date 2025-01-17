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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->text('body'); 
            
            $table->foreignId('init_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('init_doctor_id')
                  ->nullable()
                  ->constrained('doctors')
                  ->onDelete('cascade');

            $table->foreignId('assigned_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->enum('department', ['finance', 'operation', 'management', 'it']);
            $table->enum('category', ['question', 'suggestion', 'complaint']);
            $table->enum('status', ['new', 'pending', 'customer_reply', 'complete', 'user_reply']);
            
            $table->text('attachment')->nullable();
            
            $table->enum('priority', ['low','medium','high']);
            
            $table->dateTime('closed_at')->nullable();

            $table->foreignId('closed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');


            $table->foreignId('branch_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
