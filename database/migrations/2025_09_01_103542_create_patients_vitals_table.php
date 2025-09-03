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
        Schema::create('patients_vitals', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
             $table->unsignedBigInteger('patient_id')->index();
            $table->unsignedBigInteger('vital_id')->index();
            $table->string('reference_range', 100)->index();
            $table->dateTime('messure_date')->nullable()->index();

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients_vitals');
    }
};
