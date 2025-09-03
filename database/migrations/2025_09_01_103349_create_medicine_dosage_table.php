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
        Schema::create('medicine_dosage', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);

            $table->unsignedBigInteger('medicine_category_id')->nullable()->index();
            $table->string('dosage', 100)->index(); // NOT NULL
            $table->unsignedBigInteger('units_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('medicine_category_id')->references('id')->on('medicine_category')->onDelete('set null');
            $table->foreign('units_id')->references('id')->on('unit')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_dosage');
    }
};