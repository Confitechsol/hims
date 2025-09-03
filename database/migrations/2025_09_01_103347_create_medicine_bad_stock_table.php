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
        Schema::create('medicine_bad_stock', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('medicine_batch_details_id')->nullable()->index();
            $table->unsignedBigInteger('pharmacy_id')->nullable()->index();

            $table->date('outward_date')->index(); // NOT NULL
            $table->date('expiry_date')->index();  // NOT NULL

            $table->string('batch_no', 100)->index(); // NOT NULL
            $table->string('quantity', 20)->index();  // NOT NULL

            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('medicine_batch_details_id')->references('id')->on('medicine_batch_details')->onDelete('set null');
            $table->foreign('pharmacy_id')->references('id')->on('pharmacy')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_bad_stock');
    }
};