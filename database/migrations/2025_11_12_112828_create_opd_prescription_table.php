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
        Schema::create('opd_prescription', function (Blueprint $table) {
            $table->id();
            $table->integer('opd_id')->nullable();
            $table->integer('visit_id')->nullable();
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
            $table->timestamps();

            $table->foreign('opd_id')
                ->references('id')
                ->on('opd_details')
                ->onDelete('cascade');
            $table->foreign('visit_id')
                ->references('id')
                ->on('opd_visits')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_prescription');
    }
};