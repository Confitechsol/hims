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
        Schema::create('ipd_prescription_basic', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('ipd_id')->nullable()->index();
            $table->unsignedBigInteger('visit_details_id')->nullable()->index();

            $table->text('attachment');
            $table->text('attachment_name');
            $table->text('header_note')->nullable();
            $table->text('footer_note')->nullable();
            $table->text('finding_description')->nullable();

            $table->string('is_finding_print', 100)->nullable();
            $table->date('date')->index();

            $table->unsignedBigInteger('generated_by')->nullable()->index();
            $table->unsignedBigInteger('prescribe_by')->nullable()->index();
            $table->timestamps();
            $table->foreign('ipd_id')->references('id')->on('ipd_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_prescription_basic');
    }
};