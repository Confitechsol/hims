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
        Schema::create('letterhead_category', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);

            $table->string('name', 100)->nullable()->index();
            $table->string('header_text', 255)->nullable();

            $table->integer('enable_view')->default(0);
            $table->integer('enable_add')->default(0);
            $table->integer('enable_edit')->default(0);
            $table->integer('enable_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letterhead_category');
    }
};