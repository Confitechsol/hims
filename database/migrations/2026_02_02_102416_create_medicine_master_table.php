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
        Schema::create('medicine_master', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->double('price')->default(0);
            $table->string('medicine_type', 255)->nullable();
            $table->string('manufacturer_name', 255)->nullable();
            $table->string('pack_size_label', 255)->nullable();
            $table->text('short_composition1')->nullable();
            $table->text('short_composition2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_master');
    }
};
