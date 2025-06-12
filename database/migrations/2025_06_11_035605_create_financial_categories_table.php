<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('financial_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم التصنيف
            $table->enum('type', ['deposit', 'withdrawal']); // نوع التصنيف (إيداع - سحب)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_categories');
    }
};
