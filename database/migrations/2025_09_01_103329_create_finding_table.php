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
        Schema::create('finding', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('name', 200)->index();

            $table->text('description')->nullable();

            $table->unsignedBigInteger('finding_category_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('finding_category_id')->references('id')->on('finding_category')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finding');
    }
};