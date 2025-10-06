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
        Schema::create('pathology', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('test_name', 100)->nullable()->index();
            $table->string('short_name', 100)->nullable()->index();
            $table->string('test_type', 100)->nullable()->index();
            $table->unsignedBigInteger('pathology_category_id')->nullable()->index();
            $table->string('unit', 50);
            $table->string('sub_category', 50);
            $table->string('report_days', 50);
            $table->string('method', 50);
            $table->unsignedBigInteger('charge_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology');
    }
};