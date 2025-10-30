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
        Schema::create('ambulance_call', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('case_reference_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();

            $table->string('contact_no', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('vehicle_model', 20)->nullable();
            $table->string('driver', 100);
            $table->dateTime('date')->nullable();
            $table->string('call_from', 200);
            $table->string('call_to', 200);

            $table->unsignedBigInteger('charge_category_id')->nullable();
            $table->unsignedBigInteger('charge_id')->nullable();

            $table->integer('standard_charge')->nullable();
            $table->float('discount_percentage', 10, 2)->default(0.00);
            $table->float('discount', 10, 2)->default(0.00);
            $table->float('tax_percentage', 10, 2)->nullable();
            $table->float('amount', 10, 2)->default(0.00);
            $table->float('net_amount', 10, 2)->nullable();

            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambulance_call');
    }
};