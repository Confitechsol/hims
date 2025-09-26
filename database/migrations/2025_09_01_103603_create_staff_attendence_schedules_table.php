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
        Schema::create('staff_attendence_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
                        $table->unsignedBigInteger('staff_attendence_type_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();

            $table->time('entry_time_from')->nullable();
            $table->time('entry_time_to')->nullable();
            $table->time('total_institute_hour')->nullable();

            $table->integer('is_active')->default(0);

            $table->timestamp('created_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendence_schedules');
    }
};
