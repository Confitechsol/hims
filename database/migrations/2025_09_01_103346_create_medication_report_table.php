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
        Schema::create('medication_report', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('medicine_dosage_id')->nullable()->index();
            $table->unsignedBigInteger('pharmacy_id')->nullable()->index();
            $table->unsignedBigInteger('opd_details_id')->nullable()->index();
            $table->unsignedBigInteger('ipd_id')->nullable()->index();

            $table->date('date')->index(); // NOT NULL
            $table->time('time')->index(); // NOT NULL

            $table->text('remark')->nullable();

            $table->unsignedBigInteger('generated_by')->nullable()->index();
            $table->timestamps();
            $table->foreign('medicine_dosage_id')->references('id')->on('medicine_dosage')->onDelete('set null');
            $table->foreign('pharmacy_id')->references('id')->on('pharmacy')->onDelete('set null');
            $table->foreign('opd_details_id')->references('id')->on('opd_details')->onDelete('set null');
            $table->foreign('ipd_id')->references('id')->on('ipd_details')->onDelete('set null');
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_report');
    }
};