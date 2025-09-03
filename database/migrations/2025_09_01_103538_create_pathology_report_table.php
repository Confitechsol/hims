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
        Schema::create('pathology_report', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('pathology_bill_id')->nullable()->index();
            $table->unsignedBigInteger('pathology_id')->nullable()->index();
            $table->string('customer_type', 50)->nullable();
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->date('reporting_date')->nullable()->index();
            $table->date('parameter_update')->nullable()->index();
            $table->float('tax_percentage', 10, 2)->default(0.00)->index();
            $table->float('apply_charge', 10, 2)->nullable()->index();
            $table->date('collection_date')->nullable()->index();
            $table->unsignedBigInteger('collection_specialist')->nullable()->index();
            $table->string('pathology_center', 250)->nullable();
            $table->unsignedBigInteger('approved_by')->nullable()->index();
            $table->string('patient_name', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('pathology_report', 255)->nullable();
            $table->text('report_name')->nullable();
            $table->text('pathology_result')->nullable();
            $table->timestamps();
            $table->foreign('pathology_bill_id')->references('id')->on('pathology_billing')->onDelete('cascade');
            $table->foreign('pathology_id')->references('id')->on('pathology')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_report');
    }
};