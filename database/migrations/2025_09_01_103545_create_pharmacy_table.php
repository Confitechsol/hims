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
        Schema::create('pharmacy', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('medicine_name', 200)->nullable()->index();
            $table->unsignedBigInteger('medicine_category_id')->nullable()->index();

            $table->text('medicine_image'); // NOT NULL

            $table->string('medicine_company', 100)->nullable()->index();
            $table->string('medicine_composition', 100)->nullable()->index();
            $table->string('medicine_group', 100)->nullable()->index();

            $table->string('unit', 50)->nullable()->index();
            $table->string('min_level', 50)->nullable()->index();
            $table->string('reorder_level', 50)->nullable()->index();

            $table->float('vat')->nullable()->index();
            $table->string('unit_packing', 50)->nullable()->index();
            $table->string('vat_ac', 50)->nullable()->index();

            $table->string('rack_number', 255)->index(); // NOT NULL
            $table->text('note')->nullable();

            $table->string('is_active', 10); // NOT NULL
            $table->timestamp('created_at')->useCurrent(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy');
    }
};
