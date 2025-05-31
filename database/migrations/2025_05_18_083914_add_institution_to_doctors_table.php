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
            // drop institution_id column if it exists
            if (Schema::hasColumn('doctors', 'institution_id')) {
                $table->dropForeign(['institution_id']);
                $table->dropColumn('institution_id');
            }
            // add institution as nullable string
            $table->string('institution')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // drop institution column if it exists
            if (Schema::hasColumn('doctors', 'institution')) {
                $table->dropColumn('institution');
            }
            // add institution_id as foreign key
            $table->foreignId('institution_id')->nullable()->constrained('institutions')->after('id')->cascadeOnDelete();
        });
    }
};
