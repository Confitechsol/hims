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
        Schema::create('blood_issue', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('case_reference_id')->nullable();
            $table->unsignedBigInteger('blood_donor_cycle_id')->nullable();
            $table->dateTime('date_of_issue')->nullable();
            $table->unsignedBigInteger('hospital_doctor')->nullable();
            $table->text('reference')->nullable();
            $table->unsignedBigInteger('charge_id')->nullable();
            $table->integer('standard_charge'); // NOT NULL
            $table->float('tax_percentage', 10, 2);
            $table->float('discount_percentage', 10, 2)->default(0.00);
            $table->float('amount', 10, 2)->nullable();
            $table->float('net_amount', 10, 2);
            $table->text('institution')->nullable();
            $table->text('technician')->nullable();
            $table->text('remark')->nullable();
            $table->unsignedBigInteger('organisation_id')->nullable();
            $table->date('insurance_validity')->nullable();
            $table->string('insurance_id', 250)->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_issue');
    }
};
