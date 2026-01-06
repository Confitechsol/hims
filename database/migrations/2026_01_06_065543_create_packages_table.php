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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8)->nullable();
            $table->string('branch_id', 8)->nullable();
            $table->string('name', 255);
            $table->string('account_head', 100)->nullable();
            $table->decimal('gst_amount', 10, 2)->default(0.00);
            $table->decimal('package_rate', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->string('status', 50)->default('active');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->index(['hospital_id', 'branch_id']);
            $table->index('status');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
