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
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('supplier_bill_basic_id')->nullable()->index();
            $table->unsignedBigInteger('pharmacy_id')->nullable()->index();

            $table->dateTime('inward_date')->index(); // NOT NULL
            $table->date('expiry')->index();          // NOT NULL

            $table->string('batch_no', 100)->index();
            $table->string('packing_qty', 100);
            $table->string('purchase_rate_packing', 100);
            $table->string('quantity', 200);

            $table->float('mrp', 10, 2)->default(0.00)->index();
            $table->float('purchase_price', 10, 2)->default(0.00)->index();
            $table->float('tax', 10, 2)->default(0.00)->index();
            $table->float('sale_rate', 10, 2)->default(0.00)->index();
            $table->float('batch_amount', 10, 2)->default(0.00)->index();
            $table->float('amount', 10, 2)->default(0.00)->index();

            $table->integer('available_quantity')->nullable()->index();
            $table->timestamps();
            $table->foreign('supplier_bill_basic_id')->references('id')->on('supplier_bill_basic')->onDelete('set null');
            $table->foreign('pharmacy_id')->references('id')->on('pharmacy')->onDelete('set null');
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