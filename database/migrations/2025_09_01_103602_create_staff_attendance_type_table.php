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
        Schema::create('staff_attendance_type', function (Blueprint $table) {
            
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('type', 200);
            $table->string('key_value', 200);
            $table->string('is_active', 50);

            $table->string('long_lang_name', 250)->nullable();
            $table->string('long_name_style', 250)->nullable();

            $table->integer('for_schedule')->default(0);

            $table->timestamp('created_at')->useCurrent()->nullable()->useCurrentOnUpdate();
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendance_type');
    }
};
