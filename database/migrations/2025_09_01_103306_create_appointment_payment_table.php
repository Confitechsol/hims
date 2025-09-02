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
        Schema::create('appointment_payment', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('charge_id')->nullable();
            $table->float('standard_amount', 10, 2)->default(0.00);
            $table->float('tax', 10, 2)->default(0.00);
            $table->float('discount_percentage', 10, 2)->default(0.00);
            $table->float('paid_amount', 10, 2)->nullable();
            $table->string('payment_mode', 50)->nullable();
            $table->string('payment_type', 100);
            $table->string('transaction_id', 255)->nullable();
            $table->string('note', 100)->nullable();
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_payment');
    }
};
