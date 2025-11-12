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
        Schema::create('pharmacy_bill_basic', function (Blueprint $table) {
            $table->id();
            $table->datetime('date')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->unsignedBigInteger('ipd_prescription_basic_id')->nullable()->index();
            $table->unsignedBigInteger('case_reference_id')->nullable()->index();
            $table->string('customer_name', 50)->nullable();
            $table->string('customer_type', 50)->nullable();
            $table->string('doctor_name', 50)->nullable();
            $table->string('file', 200)->nullable();
            $table->decimal('total', 10, 2)->default(0.00);
            $table->decimal('discount_percentage', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('tax_percentage', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2)->default(0.00);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable()->index();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('case_reference_id')->references('id')->on('case_references')->onDelete('cascade');
            $table->foreign('generated_by')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('ipd_prescription_basic_id')->references('id')->on('ipd_prescription_basic')->onDelete('cascade');

            // Indexes for performance
            $table->index('customer_name');
            $table->index('customer_type');
            $table->index('doctor_name');
            $table->index('total');
            $table->index('discount_percentage');
            $table->index('discount');
            $table->index('tax_percentage');
            $table->index('tax');
            $table->index('net_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_bill_basic');
    }
};
