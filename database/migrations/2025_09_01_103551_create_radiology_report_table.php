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
        Schema::create('radiology_report', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('radiology_bill_id')->nullable()->index();
            $table->unsignedBigInteger('radiology_id')->nullable()->index();
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->string('customer_type', 50)->nullable()->index();
            $table->string('patient_name', 100)->nullable()->index();
            $table->string('consultant_doctor', 10)->index();
            $table->date('reporting_date')->nullable()->index();
            $table->date('parameter_update')->nullable()->index();
            $table->text('description')->nullable();
            $table->text('radiology_report')->nullable();
            $table->text('report_name')->nullable();
            $table->text('radiology_result')->nullable();
            $table->float('tax_percentage', 10, 2)->default(0.00)->index();
            $table->float('apply_charge', 10, 2)->default(0.00)->index();
            $table->string('radiology_center', 250)->index();
            $table->unsignedBigInteger('generated_by')->nullable()->index();
            $table->unsignedBigInteger('collection_specialist')->nullable()->index();
            $table->date('collection_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable()->index();
            $table->timestamps();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('radiology_id')->references('id')->on('radio')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_report');
    }
};