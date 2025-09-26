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
        Schema::create('bill', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('case_id')->index(); // references cases table
            $table->mediumText('attachment')->nullable();
            $table->mediumText('attachment_name')->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->string('payment_mode', 100)->nullable();
            $table->string('cheque_no', 100)->nullable();
            $table->date('cheque_date')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->mediumText('note')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill');
    }
};
