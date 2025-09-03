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
        Schema::create('income_head', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('income_category', 255)->nullable()->index();
            $table->text('description')->nullable();
            $table->string('is_active', 10)->default('yes');
            $table->string('is_deleted', 10)->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_head');
    }
};