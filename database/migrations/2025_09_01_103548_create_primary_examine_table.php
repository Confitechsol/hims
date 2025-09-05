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
        Schema::create('primary_examine', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('ipdid')->nullable()->index();
            $table->unsignedBigInteger('visit_details_id')->nullable()->index();

            $table->string('bleeding', 250)->nullable()->index();
            $table->string('headache', 250)->nullable()->index();
            $table->string('pain', 250)->nullable()->index();
            $table->string('constipation', 250)->nullable()->index();
            $table->string('urinary_symptoms', 250)->index();
            $table->string('vomiting', 250)->nullable()->index();
            $table->string('cough', 250)->nullable()->index();
            $table->string('vaginal', 250)->nullable()->index();
            $table->string('discharge', 250)->nullable()->index();
            $table->string('oedema', 250)->nullable()->index();
            $table->string('haemoroids', 250)->nullable()->index();
            $table->string('weight', 250)->index();
            $table->string('height', 11)->index();

            $table->dateTime('date')->nullable()->index();
            $table->text('general_condition');
            $table->string('finding_remark', 250);
            $table->text('pelvic_examination');
            $table->text('sp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('primary_examine');
    }
};