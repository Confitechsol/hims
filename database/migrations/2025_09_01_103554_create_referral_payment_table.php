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
        Schema::create('referral_payment', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('referral_person_id')->nullable()->index();
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->unsignedBigInteger('referral_type')->nullable()->index();
            $table->unsignedBigInteger('billing_id');
            $table->float('bill_amount', 10, 2)->default(0.00)->index();
            $table->float('percentage', 10, 2)->default(0.00)->index();
            $table->float('amount', 10, 2)->default(0.00)->index();
            $table->dateTime('date')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_payment');
    }
};
