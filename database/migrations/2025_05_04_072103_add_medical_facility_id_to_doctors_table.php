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
        Schema::table('doctors', function (Blueprint $table) {
            $table->foreignId('medical_facility_id')->nullable()->constrained('medical_facilities')->after('id');
            // موعد الزياره من الى 
            $table->string('visit_from')->nullable()->after('medical_facility_id');
            $table->string('visit_to')->nullable()->after('visit_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['medical_facility_id']);
            $table->dropColumn('medical_facility_id');
            $table->dropColumn('visit_from');
            $table->dropColumn('visit_to');
        });
    }
};
