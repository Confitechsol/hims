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
        Schema::create('pharmacy_bill_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_bill_basic_id')->nullable();
            $table->unsignedBigInteger('medicine_batch_detail_id')->nullable();
            $table->string('quantity', 100);
            $table->decimal('sale_price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('pharmacy_bill_basic_id')
                ->references('id')->on('pharmacy_bill_basic')
                ->onDelete('cascade');
            
            $table->foreign('medicine_batch_detail_id')
                ->references('id')->on('medicine_batch_details')
                ->onDelete('cascade');

            // Indexes for performance
            $table->index('quantity');
            $table->index('sale_price');
            $table->index('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_bill_detail');
    }
};

