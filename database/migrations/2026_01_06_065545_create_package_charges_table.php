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
        Schema::create('package_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->string('charge_type', 100); // Bed Charges, O.T. Charges, Doctor Charges, etc.
            $table->unsignedBigInteger('charge_category_id')->nullable();
            $table->unsignedBigInteger('charge_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->boolean('is_percentage')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index('package_id');
            $table->index('charge_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_charges');
    }
};
