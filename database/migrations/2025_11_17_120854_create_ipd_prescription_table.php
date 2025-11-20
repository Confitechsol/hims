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
        Schema::create('ipd_prescription', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number', 8)->nullable();
            $table->integer('ipd_id')->nullable();
            $table->text('header_note')->nullable();
            $table->text('footer_note')->nullable();
            $table->text('finding_description')->nullable();
            $table->string('finding_categories')->nullable();
            $table->string('findings')->nullable();
            $table->string('is_finding_print')->nullable();
            $table->string('pathology_id')->nullable();
            $table->string('radiology_id')->nullable();
            $table->date('date')->nullable();
            $table->string('notification_to')->nullable();
            $table->bigInteger('prescribed_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('ipd_id')
                ->references('id')
                ->on('ipd_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_prescription');
    }
};