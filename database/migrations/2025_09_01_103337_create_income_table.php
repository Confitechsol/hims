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
        Schema::create('income', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('inc_head_id')->nullable()->index();
            $table->string('name', 50)->nullable()->index();
            $table->string('invoice_no', 200)->index();
            $table->date('date')->nullable()->index();
            $table->float('amount', 10, 2)->default(0.00);
            $table->text('note')->nullable();
            $table->string('is_deleted', 10)->nullable()->default('no');
            $table->text('documents')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable()->index();
            $table->string('is_active', 10)->nullable()->default('yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income');
    }
};