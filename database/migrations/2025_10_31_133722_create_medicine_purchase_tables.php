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
        // Medicine Purchase (Basic Information)
        Schema::create('medicine_purchase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('medicine_supplier')->onDelete('cascade');
            $table->dateTime('purchase_date');
            $table->string('invoice_no')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->string('payment_mode')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->decimal('payment_amount', 15, 2)->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->text('payment_note')->nullable();
            $table->text('note')->nullable();
            $table->string('attachment')->nullable();
            $table->string('attachment_name')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Medicine Purchase Details (Batch Details)
        Schema::create('medicine_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_purchase_id');
            $table->unsignedBigInteger('medicine_id');
            $table->dateTime('inward_date');
            $table->string('batch_no');
            $table->date('expiry_date');
            $table->decimal('mrp', 15, 2)->default(0);
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('available_quantity')->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('batch_amount', 15, 2)->nullable();
            $table->string('packing_qty')->nullable();
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('medicine_purchase_id')->references('id')->on('medicine_purchase')->onDelete('cascade');
            $table->foreign('medicine_id')->references('id')->on('pharmacy')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_purchase_details');
        Schema::dropIfExists('medicine_purchase');
    }
};
