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
        Schema::create('opd_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('charge_id');
            $table->integer('charge_category_id');
            $table->integer('charge_type_id');
            $table->integer('opd_id');
            $table->integer('qty');
            $table->decimal('standard_charge', 10, 2)->default(0);
            $table->decimal('tpa_charge', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('discount_percentage', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->text('charge_note')->nullable();
            $table->date('date');
            $table->timestamps();
            $table->foreign('charge_id')
                ->references('id')
                ->on('charges')
                ->onDelete('cascade');

            $table->foreign('charge_category_id')
                ->references('id')
                ->on('charge_categories')
                ->onDelete('cascade');

            $table->foreign('charge_type_id')
                ->references('id')
                ->on('charge_type_master')
                ->onDelete('cascade');

            $table->foreign('opd_id')
                ->references('id')
                ->on('opd_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_charges');
    }
};
