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
        Schema::create('ipd_medicines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prescription_id')->unsigned();
            $table->integer('pharmacy_id');
            $table->integer('medicine_dosage_id');
            $table->integer('dose_interval_id');
            $table->integer('dose_duration_id');
            $table->string('instruction')->nullable();
            $table->timestamps();
            $table->foreign('prescription_id')
                ->references('id')
                ->on('opd_prescription')
                ->onDelete('cascade');

            $table->foreign('pharmacy_id')
                ->references('id')
                ->on('pharmacy')
                ->onDelete('cascade');

            $table->foreign('medicine_dosage_id')
                ->references('id')
                ->on('medicine_dosage')
                ->onDelete('cascade');

            $table->foreign('dose_interval_id')
                ->references('id')
                ->on('dose_interval')
                ->onDelete('cascade');

            $table->foreign('dose_duration_id')
                ->references('id')
                ->on('dose_interval')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_medicines');
    }
};