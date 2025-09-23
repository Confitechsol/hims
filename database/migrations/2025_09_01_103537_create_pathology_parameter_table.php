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
        Schema::create('pathology_parameter', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('parameter_name', 100)->index();
            $table->string('test_value', 100)->index();
            $table->string('reference_range', 100)->index();
            $table->string('range_from', 500)->nullable()->index();
            $table->string('range_to', 500)->nullable()->index();
            $table->string('gender', 100)->index();
            $table->unsignedBigInteger('unit_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->timestamps();
            // $table->foreign('unit_')->references('id')->on('unit')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_parameter');
    }
};