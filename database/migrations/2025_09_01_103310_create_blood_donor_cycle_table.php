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
        Schema::create('blood_donor_cycle', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('blood_donor_cycle_id');
            $table->unsignedBigInteger('blood_bank_product_id')->nullable();
            $table->unsignedBigInteger('blood_donor_id')->nullable();
            $table->unsignedBigInteger('charge_id')->nullable();
            $table->date('donate_date')->nullable();
            $table->string('bag_no', 11)->nullable();
            $table->string('lot', 11)->nullable();
            $table->integer('quantity')->nullable();
            $table->float('standard_charge', 10, 2)->nullable();
            $table->float('apply_charge', 10, 2)->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->text('institution')->nullable();
            $table->text('note')->nullable();
            $table->float('discount_percentage', 10, 2)->default(0.00);
            $table->float('tax_percentage', 10, 2)->default(0.00);
            $table->string('volume', 100)->nullable();
            $table->integer('unit')->nullable();
            $table->boolean('available')->default(1);
            $table->timestamp('created_at')->useCurrent();

            // Indexes (based on MUL in your schema)
            $table->index('blood_bank_product_id');
            $table->index('blood_donor_id');
            $table->index('charge_id');
            $table->index('bag_no');
            $table->index('lot');
            $table->index('quantity');
            $table->index('standard_charge');
            $table->index('apply_charge');
            $table->index('amount');
            $table->index('volume');
            $table->index('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_donor_cycle');
    }
};
