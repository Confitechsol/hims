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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('title', 100)->nullable()->index();
            // VARCHAR(100), NULL, indexed

            $table->string('type', 50)->nullable();
            // VARCHAR(50), NULL

            $table->string('is_public', 10)->nullable()->default('No');
            // VARCHAR(10), NULL, default 'No'

            $table->text('file')->nullable();
            // TEXT, NULL

            $table->text('note')->nullable();
            // TEXT, NULL

            $table->date('date')->nullable();
            // DATE, NULL

            $table->string('is_active', 10)->nullable()->default('no');
            // VARCHAR(10), default 'no'

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
