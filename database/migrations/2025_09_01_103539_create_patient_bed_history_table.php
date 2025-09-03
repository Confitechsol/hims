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
        Schema::create('patient_bed_history', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('case_reference_id')->nullable()->index();
            $table->unsignedBigInteger('bed_group_id')->nullable()->index();
            $table->unsignedBigInteger('bed_id')->nullable()->index();

            $table->text('revert_reason')->nullable();

            $table->dateTime('from_date')->nullable()->index();
            $table->dateTime('to_date')->nullable()->index();

            $table->string('is_active', 20)->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_bed_history');
    }
};
