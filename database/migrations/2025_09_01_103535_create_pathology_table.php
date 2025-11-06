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
            $table->string('hospital_id', 8)->nullable();
            $table->string('branch_id', 8)->nullable();
            $table->string('test_name', 50)->nullable()->index();
            $table->string('short_name', 20)->nullable()->index();
            $table->string('test_type', 15)->nullable();
            $table->unsignedBigInteger('pathology_category_id')->nullable()->index();
            $table->string('sub_category', 25)->nullable();
            $table->string('method', 25)->nullable();
            $table->integer('report_days')->nullable();
            $table->unsignedBigInteger('charge_category_id')->nullable()->index();
            $table->unsignedBigInteger('charge_id')->nullable()->index();
            $table->decimal('standard_charge', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
            
            $table->foreign('pathology_category_id')->references('id')->on('pathology_category')->onDelete('set null');
            $table->foreign('charge_category_id')->references('id')->on('charge_categories')->onDelete('set null');
            $table->foreign('charge_id')->references('id')->on('charges')->onDelete('set null');
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