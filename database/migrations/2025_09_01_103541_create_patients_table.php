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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->integer('lang_id')->nullable();
            $table->string('patient_name', 100)->nullable()->index();
            $table->date('dob')->nullable()->index();
            $table->integer('age')->index();
            $table->integer('month')->index();
            $table->integer('day');
            $table->date('as_of_date')->nullable();
            $table->text('image')->nullable();
            $table->string('mobileno', 100)->nullable()->index();
            $table->string('email', 100)->nullable()->index();
            $table->string('gender', 100)->nullable()->index();
            $table->string('marital_status', 100)->index();
            $table->string('blood_group', 200);
            $table->integer('blood_bank_product_id')->nullable()->index();
            $table->text('address')->nullable()->index();
            $table->string('guardian_name', 100)->nullable()->index();
            $table->string('patient_type', 200);
            $table->string('identification_number', 60);
            $table->string('known_allergies', 200);
            $table->string('note', 200);
            $table->string('is_ipd', 200);
            $table->string('app_key', 200);
            $table->integer('organisation_id')->nullable()->index();
            $table->string('insurance_id', 250)->nullable();
            $table->date('insurance_validity')->nullable();
            $table->string('is_dead', 255)->default('no');
            $table->integer('is_antenatal');
            $table->string('is_active', 255)->nullable()->default('no');
            $table->string('tpa_code', 255)->nullable();
            $table->string('tpa_validity', 255)->nullable();
            $table->date('disable_at')->nullable();

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
