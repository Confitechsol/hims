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
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
                        $table->dateTime('date')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->unsignedBigInteger('ipd_prescription_basic_id')->nullable()->index();
            $table->unsignedBigInteger('case_reference_id')->nullable()->index();

            $table->string('customer_name', 50)->nullable()->index();
            $table->string('customer_type', 50)->nullable()->index();
            $table->string('doctor_name', 50)->nullable()->index();

            $table->string('file', 200); // NOT NULL

            $table->float('total', 10, 2)->default(0.00)->nullable()->index();
            $table->float('discount_percentage', 10, 2)->default(0.00)->nullable()->index();
            $table->float('discount', 10, 2)->default(0.00)->nullable()->index();
            $table->float('tax_percentage', 10, 2)->default(0.00)->nullable()->index();
            $table->float('tax', 10, 2)->default(0.00)->nullable()->index();
            $table->float('net_amount', 10, 2)->default(0.00)->nullable()->index();

            $table->text('note')->nullable();

            $table->unsignedBigInteger('generated_by')->nullable()->index();

            $table->timestamp('created_at')->useCurrent();
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
