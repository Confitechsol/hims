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
        Schema::create('radiology_billing', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->unsignedBigInteger('case_reference_id')->nullable()->index();
            $table->unsignedBigInteger('ipd_prescription_basic_id')->nullable()->index();
            $table->unsignedBigInteger('doctor_id')->nullable()->index();
            $table->dateTime('date')->nullable()->index();
            $table->string('doctor_name', 100)->index();
            $table->float('total', 10, 2);
            $table->float('discount_percentage', 10, 2);
            $table->float('discount', 10, 2);
            $table->float('tax_percentage', 10, 2);
            $table->float('tax', 10, 2);
            $table->float('net_amount', 10, 2);
            $table->unsignedBigInteger('transaction_id')->nullable()->index();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('organisation_id')->nullable()->index();
            $table->date('insurance_validity')->nullable()->index();
            $table->string('insurance_id', 250)->nullable()->index();
            $table->unsignedBigInteger('generated_by')->nullable()->index();
            $table->timestamps();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            // $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
            $table->foreign('organisation_id')->references('id')->on('organisation')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_billing');
    }
};