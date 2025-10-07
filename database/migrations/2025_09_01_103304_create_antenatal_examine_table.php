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
        Schema::create('antenatal_examine', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('primary_examine_id');
            $table->unsignedBigInteger('visit_details_id')->nullable();
            $table->unsignedBigInteger('ipdid')->nullable();

            $table->string('uter_size', 250)->nullable();
            $table->string('uterus_size', 250)->nullable();
            $table->string('presentation_position', 250)->nullable();
            $table->string('brim_presentation', 250)->nullable();
            $table->string('foeta_heart', 250)->nullable();
            $table->string('blood_pressure', 250)->nullable();
            $table->string('antenatal_oedema', 250)->nullable();
            $table->string('antenatal_weight', 250)->nullable();
            $table->string('urine_sugar', 250)->nullable();
            $table->string('urine', 250)->nullable();

            $table->text('remark')->nullable();
            $table->text('next_visit')->nullable();

            $table->timestamp('created_at')->useCurrent();

            // 🔹 Indexes as per MUL in schema
            $table->index('primary_examine_id');
            $table->index('visit_details_id');
            $table->index('ipdid');
            $table->index('uter_size');
            $table->index('uterus_size');
            $table->index('presentation_position');
            $table->index('brim_presentation');
            $table->index('foeta_heart');
            $table->index('blood_pressure');
            $table->index('antenatal_oedema');
            $table->index('antenatal_weight');
            $table->index('urine_sugar');
            $table->index('urine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antenatal_examine');
    }
};