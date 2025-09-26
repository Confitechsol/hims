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
        Schema::create('item_store', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('item_store', 255)->index();
            $table->text('description');
            $table->string('is_active', 20)->index();
            $table->string('code', 255)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_store');
    }
};