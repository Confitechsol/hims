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
        Schema::create('visit_details', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedInteger('opd_details_id')->nullable()->index();
            $table->unsignedInteger('organisation_id')->nullable()->index();
            $table->unsignedInteger('patient_charge_id')->nullable()->index();
            $table->unsignedInteger('transaction_id')->nullable()->index();
            $table->unsignedInteger('cons_doctor')->nullable()->index();

            $table->string('case_type', 200)->index();
            $table->dateTime('appointment_date')->nullable()->index();
            $table->unsignedInteger('symptoms_type')->nullable()->index();
            $table->text('symptoms')->nullable();

            $table->string('bp', 100)->nullable()->index();
            $table->string('height', 100)->nullable()->index();
            $table->string('weight', 100)->nullable()->index();
            $table->string('pulse', 200)->nullable()->index();
            $table->string('temperature', 200)->nullable()->index();
            $table->string('respiration', 200)->nullable()->index();

            $table->string('known_allergies', 100)->nullable()->index();
            $table->string('patient_old', 50)->nullable()->index();
            $table->string('casualty', 200)->nullable()->index();
            $table->string('refference', 200)->nullable()->index();

            $table->date('date')->nullable()->index();
            $table->text('note')->nullable();
            $table->mediumText('note_remark')->nullable();

            $table->string('payment_mode', 100)->index();
            $table->unsignedInteger('generated_by')->nullable()->index();
            $table->string('live_consult', 50)->nullable()->index();

            $table->integer('is_antenatal')->index(); // NOT NULL
            $table->string('can_delete', 11)->default('yes'); // default yes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_details');
    }
};
