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
        Schema::create('transactions_processing', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->integer('gateway_ins_id'); // gateway_ins_id INT(11) NOT NULL
            $table->string('type', 100)->nullable(); // type VARCHAR(100) NULL
            $table->string('section', 50); // section VARCHAR(50) NOT NULL

            // Foreign key related fields (nullable)
            $table->integer('patient_id')->nullable();
            $table->integer('case_reference_id')->nullable();
            $table->integer('opd_id')->nullable();
            $table->integer('ipd_id')->nullable();
            $table->integer('pharmacy_bill_basic_id')->nullable();
            $table->integer('pathology_billing_id')->nullable();
            $table->integer('radiology_billing_id')->nullable();
            $table->integer('blood_donor_cycle_id')->nullable();
            $table->integer('blood_issue_id')->nullable();
            $table->integer('ambulance_call_id')->nullable();
            $table->integer('appointment_id')->nullable();

            // Attachments
            $table->string('attachment', 250)->nullable();
            $table->text('attachment_name')->nullable();

            // Payment details
            $table->string('amount_type', 10)->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->string('payment_mode', 100)->nullable();
            $table->string('cheque_no', 100)->nullable();
            $table->date('cheque_date')->nullable();
            $table->dateTime('payment_date')->nullable();

            $table->text('note')->nullable();
            $table->integer('received_by')->nullable();
            $table->integer('bill_id')->nullable();

            // Timestamp
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_processing');
    }
};
