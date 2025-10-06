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
        Schema::create('gateway_ins', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('online_appointment_id')->nullable()->index();
            $table->string('type', 30)->index();
            $table->string('gateway_name', 50)->index();
            $table->string('module_type', 255)->index();
            $table->string('unique_id', 255)->index();
            $table->mediumText('parameter_details');
            $table->string('payment_status', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateway_ins');
    }
};