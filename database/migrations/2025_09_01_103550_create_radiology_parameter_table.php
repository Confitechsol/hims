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
        Schema::create('radiology_parameter', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('parameter_name', 100)->index();
            $table->string('test_value', 100)->index();
            $table->string('reference_range', 100)->index();
            $table->string('range_from', 500)->nullable()->index();
            $table->string('range_to', 500)->nullable()->index();
            $table->string('gender', 100)->index();
            $table->string('unit', 100)->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_parameter');
    }
};