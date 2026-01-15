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
        Schema::create('ipd_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ipd_id');
            $table->unsignedBigInteger('package_id');
            $table->date('applied_date');
            $table->unsignedBigInteger('applied_by')->nullable();
            $table->decimal('package_rate', 10, 2)->default(0.00);
            $table->decimal('discount_percentage', 5, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('gst_amount', 10, 2)->default(0.00);
            $table->decimal('final_amount', 10, 2)->default(0.00);
            $table->string('status', 50)->default('applied'); // applied, completed, cancelled
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->index('ipd_id');
            $table->index('package_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_packages');
    }
};
