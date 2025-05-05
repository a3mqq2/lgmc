<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('doctor_mail_items', function (Blueprint $table) {
            $table->enum('work_mention', ['with', 'without'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('doctor_mail_items', function (Blueprint $table) {
            $table->dropColumn('work_mention');
        });
    }
};
