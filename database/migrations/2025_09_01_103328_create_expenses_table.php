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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('exp_head_id')->nullable()->index();

            $table->string('name', 50)->nullable()->index();

            $table->string('invoice_no', 200)->index();

            $table->date('date')->nullable()->index();

            $table->float('amount', 10, 2)->nullable()->index();

            $table->text('documents')->nullable();

            $table->text('note')->nullable();

            $table->string('is_active', 10)->default('yes')->nullable();

            $table->string('is_deleted', 10)->default('no')->nullable();

            $table->unsignedBigInteger('generated_by')->nullable()->index();
            $table->timestamps();
            $table->foreign('exp_head_id')->references('id')->on('expense_head')->onDelete('set null');
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};