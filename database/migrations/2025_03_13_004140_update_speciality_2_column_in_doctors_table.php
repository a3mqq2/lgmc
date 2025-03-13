<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Step 1: Drop the foreign key constraint on specialty_2_id (if it exists)
        Schema::table('doctors', function ($table) {
            if (Schema::hasColumn('doctors', 'specialty_2_id')) {
                // Drop the foreign key constraint. Adjust the key name if necessary.
                $table->dropForeign(['specialty_2_id']);
            }
        });
        
        // Step 2: Rename the column and convert its type using a raw statement.
        // MariaDB syntax requires: ALTER TABLE `table` CHANGE `old_column` `new_column` new_type
        DB::statement("ALTER TABLE `doctors` CHANGE `specialty_2_id` `specialty_2` VARCHAR(255) NULL");
    }

    public function down()
    {
        // Reverse the column rename and type change.
        // Note: Adjust the type and constraints to match your original column definition.
        DB::statement("ALTER TABLE `doctors` CHANGE `specialty_2` `specialty_2_id` INT UNSIGNED NOT NULL");
        
        // Optionally, if needed, recreate the foreign key constraint.
        // Schema::table('doctors', function ($table) {
        //     $table->foreign('specialty_2_id')->references('id')->on('specialties')->onDelete('cascade');
        // });
    }
};
