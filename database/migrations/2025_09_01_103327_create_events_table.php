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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('event_title', 200)->index();
            // VARCHAR(200), NOT NULL, indexed

            $table->text('event_description')->nullable();
            // TEXT, NULL

            $table->dateTime('start_date')->index();
            // DATETIME, NOT NULL, indexed

            $table->dateTime('end_date')->index();
            // DATETIME, NOT NULL, indexed

            $table->string('event_type', 100)->index();
            // VARCHAR(100), NOT NULL, indexed

            $table->string('event_color', 200)->index();
            // VARCHAR(200), NOT NULL, indexed

            $table->string('event_for', 100);
            // VARCHAR(100), NOT NULL

            $table->unsignedBigInteger('role_id')->nullable()->index();
            // INT(11), NULL, indexed

            $table->string('is_active', 10);
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};