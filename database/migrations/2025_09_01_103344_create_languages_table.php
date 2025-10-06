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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('language', 50)->nullable()->index();
            $table->string('short_code', 255)->index();
            $table->string('country_code', 255)->index();
            $table->string('is_deleted', 10)->default('yes')->index();
            $table->string('is_rtl', 10)->default('no')->index();
            $table->string('is_active', 255)->nullable()->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};