<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::create('pharmacy', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_name', 200)->nullable()->index();
            $table->unsignedBigInteger('medicine_category_id')->nullable();
            $table->text('medicine_image')->nullable();
            $table->unsignedBigInteger('medicine_company')->nullable()->comment('Company ID from pharmacy_company');
            $table->string('medicine_composition', 100)->nullable();
            $table->unsignedBigInteger('medicine_group')->nullable()->comment('Group ID from medicine_group');
            $table->unsignedBigInteger('unit')->nullable()->comment('Unit ID from unit');
            $table->string('min_level', 50)->nullable();
            $table->string('reorder_level', 50)->nullable();
            $table->decimal('gst_percentage', 5, 2)->nullable()->comment('GST Rate: 5%, 12%, 18%, 28%');
            $table->string('unit_packing', 50)->nullable();
            $table->string('rack_number', 255)->nullable();
            $table->text('note')->nullable();
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('medicine_category_id')
                ->references('id')->on('medicine_categories')
                ->onDelete('cascade');

            // Index for performance
            $table->index('medicine_company');
            $table->index('medicine_composition');
            $table->index('medicine_group');
            $table->index('unit');
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy');
    }
};

