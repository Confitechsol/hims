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
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
              $table->date('date'); // date (required)
            $table->unsignedBigInteger('staff_id')->nullable(); // staff_id (FK - nullable)
            $table->unsignedBigInteger('staff_attendance_type_id')->nullable(); // staff_attendance_type_id (FK - nullable)

            $table->integer('biometric_attendence')->default(0);
            $table->text('biometric_device_data')->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->string('remark', 200);
            $table->integer('is_active');

            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();

            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->nullable();

            // (Optional) Foreign key constraints if related tables exist
            // $table->foreign('staff_id')->references('id')->on('staff');
            // $table->foreign('staff_attendance_type_id')->references('id')->on('staff_attendance_types');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendance');
    }
};
