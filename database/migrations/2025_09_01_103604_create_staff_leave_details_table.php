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
        Schema::create('staff_leave_details', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('staff_id')->nullable();      // staff_id
            $table->unsignedBigInteger('leave_type_id')->nullable(); // leave_type_id

            $table->string('alloted_leave', 100); // alloted_leave

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate(); // created_at with ON UPDATE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_leave_details');
    }
};
