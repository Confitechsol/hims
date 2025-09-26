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
        Schema::create('ipd_prescription_details', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('basic_id')->nullable()->index();
            $table->unsignedBigInteger('pharmacy_id')->nullable()->index();
            $table->unsignedBigInteger('dosage')->nullable()->index();
            $table->unsignedBigInteger('dose_interval_id')->nullable()->index();
            $table->unsignedBigInteger('dose_duration_id')->nullable()->index();

            $table->text('instruction')->nullable();

            $table->timestamps();
            $table->foreign('basic_id')->references('id')->on('ipd_prescription_basic')->onDelete('cascade');
            $table->foreign('pharmacy_id')->references('id')->on('pharmacy')->onDelete('cascade');
            $table->foreign('dose_interval_id')->references('id')->on('dose_interval')->onDelete('cascade');
            $table->foreign('dose_duration_id')->references('id')->on('dose_duration')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_prescription_details');
    }
};