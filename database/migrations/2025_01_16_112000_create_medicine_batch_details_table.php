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
        Schema::create('medicine_batch_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id')->nullable()->index();
            $table->string('batch_no', 50)->nullable();
            $table->date('expiry')->nullable();
            $table->string('packing_qty', 50)->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('sale_rate', 10, 2)->nullable();
            $table->decimal('mrp', 10, 2)->nullable();
            $table->string('quantity', 50)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('inward_date')->nullable();
            $table->string('purchase_no', 50)->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('pharmacy_id')->references('id')->on('pharmacy')->onDelete('cascade');

            // Indexes for performance
            $table->index('batch_no');
            $table->index('expiry');
            $table->index('purchase_price');
            $table->index('sale_rate');
            $table->index('mrp');
            $table->index('quantity');
            $table->index('amount');
            $table->index('inward_date');
            $table->index('purchase_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_batch_details');
    }
};
