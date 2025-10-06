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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('type', 100)->nullable()->index(); // type
            $table->string('section', 50)->index(); // section

            // Foreign IDs (all nullable)
            $table->integer('patient_id')->nullable()->index();
            $table->integer('case_reference_id')->nullable()->index();
            $table->integer('opd_id')->nullable()->index();
            $table->integer('ipd_id')->nullable()->index();
            $table->integer('pharmacy_bill_basic_id')->nullable()->index();
            $table->integer('pathology_billing_id')->nullable()->index();
            $table->integer('radiology_billing_id')->nullable()->index();
            $table->integer('blood_donor_cycle_id')->nullable()->index();
            $table->integer('blood_issue_id')->nullable()->index();
            $table->integer('ambulance_call_id')->nullable()->index();
            $table->integer('appointment_id')->nullable()->index();
            $table->integer('bill_id')->nullable()->index();

            // Attachments
            $table->text('attachment')->nullable();
            $table->text('attachment_name')->nullable();

            // Payment details
            $table->string('amount_type', 10)->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->string('payment_mode', 100)->nullable();
            $table->string('cheque_no', 100)->nullable();
            $table->date('cheque_date')->nullable();
            $table->dateTime('payment_date')->nullable();

            // Other info
            $table->text('note')->nullable();
            $table->integer('received_by')->nullable();

            // Timestamps
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
