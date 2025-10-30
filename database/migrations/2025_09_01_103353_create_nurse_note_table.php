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
        Schema::create('nurse_note', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->dateTime('date')->index(); // required
            $table->unsignedBigInteger('ipd_id')->nullable()->index();
            $table->unsignedBigInteger('staff_id')->nullable()->index();

            $table->text('note')->nullable();
            $table->text('comment')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->foreign('ipd_id')->references('id')->on('ipd_detail')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurse_note');
    }
};