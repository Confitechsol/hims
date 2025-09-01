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
        Schema::create('birth_report', function (Blueprint $table) {
            $table->id();
            $table->string('child_name', 200);
            $table->text('child_pic');
            $table->string('gender', 200);
            $table->dateTime('birth_date')->nullable();
            $table->string('weight', 200);
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('case_reference_id')->nullable();
            $table->string('contact', 20);
            $table->text('mother_pic');
            $table->string('father_name', 200);
            $table->text('father_pic');
            $table->mediumText('birth_report')->nullable();
            $table->text('document');
            $table->text('address')->nullable();
            $table->string('is_active', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birth_report');
    }
};
