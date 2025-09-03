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
        Schema::create('patient_timeline', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
             $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->string('title', 200)->index();
            $table->dateTime('timeline_date')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('document', 255);
            $table->string('status', 100);
            $table->dateTime('date')->nullable()->index();
            $table->string('generated_users_type', 100);
            $table->unsignedBigInteger('generated_users_id')->nullable()->index();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_timeline');
    }
};
