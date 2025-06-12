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
        Schema::table('doctor_mails', function (Blueprint $table) {
            // 'edit_note',        // Add this
            // 'approved_at',      // Add this
            // 'approved_by',      // Add this
            // 'rejected_at',      // Add this
            // 'rejected_by',      // Add this

            $table->text('edit_note')->nullable();          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_mails', function (Blueprint $table) {
            $table->dropColumn('edit_note');
        });
    }
};
