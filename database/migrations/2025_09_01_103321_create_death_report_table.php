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
        Schema::create('death_report', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            // INT(11), NULL, indexed

            $table->unsignedBigInteger('case_reference_id')->nullable()->index();
            // INT(11), NULL, indexed

            $table->text('attachment');
            // TEXT, NOT NULL

            $table->text('attachment_name')->nullable();
            // TEXT, NULL

            $table->dateTime('death_date');
            // DATETIME, NOT NULL

            $table->string('guardian_name', 200);
            // VARCHAR(200), NOT NULL

            $table->text('death_report')->nullable();
            // TEXT, NULL

            $table->string('is_active', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('death_report');
    }
};
