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
        Schema::create('opd_visits', function (Blueprint $table) {
            $table->id();
            $table->string('visit_id');
            $table->integer('opd_id');
            $table->integer('patient_id');
            $table->timestamps();

            $table->foreign('opd_id')
                ->references('id')
                ->on('opd_details')
                ->onDelete('cascade');

            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_visits');
    }
};
