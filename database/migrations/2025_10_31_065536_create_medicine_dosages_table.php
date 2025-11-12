<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_dosages', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('medicine_category_id');
            $table->string('dosage');
            $table->unsignedBigInteger('unit_id');
            $table->timestamps();
            
            $table->foreign('medicine_category_id')->references('id')->on('medicine_categories')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('medicine_units')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_dosages');
    }
};
