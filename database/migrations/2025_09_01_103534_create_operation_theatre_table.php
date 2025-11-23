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
        Schema::create('operation_theatre', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('reference_no', 8);
            $table->unsignedBigInteger('opd_details_id')->nullable()->index();
            $table->unsignedBigInteger('ipd_details_id')->nullable()->index();
            $table->string('customer_type', 50)->nullable();
            $table->unsignedBigInteger('operation_id')->index();
            $table->dateTime('date')->nullable()->index();
            $table->string('operation_type', 100)->nullable();
            $table->unsignedBigInteger('consultant_doctor')->nullable()->index();
            $table->string('ass_consultant_1', 50)->nullable();
            $table->string('ass_consultant_2', 50)->nullable();
            $table->string('anesthetist', 50)->nullable();
            $table->string('anaethesia_type', 50)->nullable();
            $table->string('ot_technician', 100)->nullable();
            $table->string('ot_assistant', 100)->nullable();
            $table->text('result')->nullable();
            $table->text('remark')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable()->index();
            $table->timestamps();
            $table->foreign('operation_id')->references('id')->on('operation')->onDelete('cascade');
            $table->foreign('consultant_doctor')->references('id')->on('staff')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_theatre');
    }
};