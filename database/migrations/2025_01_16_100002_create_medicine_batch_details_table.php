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
            $table->unsignedBigInteger('supplier_bill_basic_id')->nullable();
            $table->unsignedBigInteger('pharmacy_id')->nullable();
            $table->dateTime('inward_date');
            $table->date('expiry');
            $table->string('batch_no', 100);
            $table->string('packing_qty', 100);
            $table->string('purchase_rate_packing', 100);
            $table->string('quantity', 200);
            $table->decimal('mrp', 10, 2)->default(0);
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('sale_rate', 10, 2)->default(0);
            $table->decimal('batch_amount', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('available_quantity')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('supplier_bill_basic_id')
                ->references('id')->on('supplier_bill_basic')
                ->onDelete('cascade');
            
            $table->foreign('pharmacy_id')
                ->references('id')->on('pharmacy')
                ->onDelete('cascade');

            // Indexes for performance
            $table->index('inward_date');
            $table->index('expiry');
            $table->index('batch_no');
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

