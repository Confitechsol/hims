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
            $table->string('hospital_id', 8);
                        $table->unsignedBigInteger('pharmacy_bill_basic_id')->nullable()->index();
            $table->unsignedBigInteger('medicine_batch_detail_id')->nullable()->index();

            $table->string('quantity', 100)->index(); // NOT NULL
            $table->float('sale_price', 10, 2)->index(); // NOT NULL
            $table->float('amount', 10, 2)->index();    // NOT NULL

            $table->timestamp('created_at')->useCurrent();
 // auto timestamp
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
