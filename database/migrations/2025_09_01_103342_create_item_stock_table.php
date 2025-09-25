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
        Schema::create('item_stock', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('item_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->string('symbol', 10)->default('+');
            $table->unsignedBigInteger('store_id')->nullable()->index();

            $table->integer('quantity')->nullable()->index();
            $table->float('purchase_price', 10, 2)->default(0.00)->index();

            $table->date('date')->nullable()->index();
            $table->text('attachment')->nullable();
            $table->text('description')->nullable();

            $table->string('is_active', 10)->default('yes');
            $table->timestamps();
            $table->foreign('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('item_supplier')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('item_store')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_stock');
    }
};