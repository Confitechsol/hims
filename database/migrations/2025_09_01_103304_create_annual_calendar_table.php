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
        Schema::create('annual_calendar', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('holiday_type');
            $table->dateTime('from_date')->nullable();
            $table->dateTime('to_date')->nullable();
            $table->text('description');

            $table->integer('is_active')->default(1);
            $table->string('holiday_color', 200);
            $table->integer('front_site')->default(0);
            $table->unsignedBigInteger('created_by');

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            $table->date('updated_at')->nullable();

            // 🔹 Add indexes (since MySQL schema had MUL)
            $table->index('holiday_type');
            $table->index('from_date');
            $table->index('to_date');
            $table->index('created_by');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_calendar');
    }
};
