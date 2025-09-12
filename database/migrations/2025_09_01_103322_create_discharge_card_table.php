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
        Schema::create('discharge_card', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('case_reference_id')->nullable()->index();
            // INT(11), NULL, indexed

            $table->unsignedBigInteger('opd_details_id')->nullable()->index();
            // INT(11), NULL, indexed

            $table->unsignedBigInteger('ipd_details_id')->nullable()->index();
            // INT(11), NULL, indexed

            $table->unsignedBigInteger('discharge_by')->nullable()->index();
            // INT(11), NULL, indexed

            $table->dateTime('discharge_date')->nullable();
            // DATETIME, NULL

            $table->integer('discharge_status');
            // INT(11), NOT NULL

            $table->dateTime('death_date')->nullable();
            // DATETIME, NULL

            $table->dateTime('refer_date')->nullable();
            // DATETIME, NULL

            $table->string('refer_to_hospital', 255)->nullable();
            // VARCHAR(255), NULL

            $table->string('reason_for_referral', 255)->nullable();
            // VARCHAR(255), NULL

            $table->string('operation', 225);
            // VARCHAR(225), NOT NULL

            $table->string('diagnosis', 255);
            // VARCHAR(255), NOT NULL

            $table->text('investigations')->nullable();
            // TEXT, NULL

            $table->string('treatment_home', 255);
            // VARCHAR(255), NOT NULL

            $table->text('note')->nullable();
            $table->timestamps();

            //$table->foreign('case_reference_id')->references('id')->on('case_references')->onDelete('set null');
            //$table->foreign('opd_details_id')->references('id')->on('opd_details')->onDelete('set null');
            //$table->foreign('ipd_details_id')->references('id')->on('ipd_details')->onDelete('set null');
            //
            // $table->foreign('discharge_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_card');
    }
};