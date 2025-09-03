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
        Schema::create('patient_id_card', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
             $table->string('title', 100)->index();
            $table->string('hospital_name', 100)->index();
            $table->string('hospital_address', 500)->index();
            $table->text('background');
            $table->text('logo');
            $table->text('sign_image');
            $table->string('header_color', 100)->index();

            $table->boolean('enable_patient_name')->index();
            $table->boolean('enable_guardian_name')->index();
            $table->boolean('enable_patient_unique_id')->index();
            $table->boolean('enable_address')->index();
            $table->boolean('enable_phone')->index();
            $table->boolean('enable_dob')->index();
            $table->boolean('enable_blood_group')->index();

            $table->boolean('status')->index();
            $table->boolean('enable_barcode')->index();

            $table->timestamp('created_at')->useCurrent();
            // If you want updated_at too:
            // $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_id_card');
    }
};
