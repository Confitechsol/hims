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
        Schema::create('doctor', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('doctor_id', 200)->unique()->nullable();
            $table->string('branch_id', 200)->nullable();

            // Basic info
            $table->string('name', 200);
            $table->string('surname', 200)->nullable();
            $table->string('email', 200)->unique();
            $table->string('contact_no', 200)->nullable();

            // Professional info
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('designation')->nullable(); // like Consultant, Senior Doctor etc.
            $table->string('qualification', 200)->nullable();
            $table->string('work_exp', 200)->nullable();
            $table->string('specialization', 200)->nullable();

            // Extra details (optional)
            $table->date('dob')->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('blood_group', 100)->nullable();

                                                   // System info
            $table->unsignedBigInteger('user_id'); // linked to users table
            $table->boolean('is_active')->default(true);

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor');
    }
};
