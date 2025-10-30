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
        Schema::create('patient_charges', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->dateTime('date')->nullable();

            $table->unsignedBigInteger('ipd_id')->nullable()->index();
            $table->unsignedBigInteger('opd_id')->nullable()->index();
            $table->integer('qty')->nullable()->index();

            $table->unsignedBigInteger('charge_id')->nullable()->index();

            $table->float('standard_charge', 10, 2)->default(0.00)->nullable()->index();
            $table->float('tpa_charge', 10, 2)->default(0.00)->nullable()->index();
            $table->float('discount_percentage', 10, 2)->default(0.00)->nullable()->index();
            $table->float('tax', 10, 2)->default(0.00)->nullable()->index();
            $table->float('apply_charge', 10, 2)->default(0.00)->nullable()->index();
            $table->float('amount', 10, 2)->default(0.00)->nullable()->index();

            $table->text('note')->nullable();

            $table->unsignedBigInteger('organisation_id')->nullable()->index();

            $table->date('insurance_validity')->nullable();
            $table->string('insurance_id', 250)->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_charges');
    }
};
