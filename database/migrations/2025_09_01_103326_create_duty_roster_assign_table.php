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
        Schema::create('duty_roster_assign', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->integer('code');

            $table->date('roster_duty_date')->nullable()->index();

            $table->unsignedBigInteger('floor_id')->nullable()->index();

            $table->unsignedBigInteger('department_id')->nullable()->index();

            $table->unsignedBigInteger('staff_id')->index();

            $table->unsignedBigInteger('duty_roster_list_id')->index();
            $table->timestamps();
           // $table->foreign('floor_id')->references('id')->on('floor')->onDelete('set null');
           // $table->foreign('department_id')->references('id')->on('department')->onDelete('set null');
           // $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
           // $table->foreign('duty_roster_list_id')->references('id')->on('duty_roster_list')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duty_roster_assign');
    }
};