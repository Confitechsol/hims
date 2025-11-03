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
        Schema::create('consultant_register', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->integer('ipd_id')->unsigned()->nullable()->index();

            $table->dateTime('date')->nullable();

            $table->date('ins_date')->nullable();

            $table->text('instruction')->nullable();

            $table->integer('cons_doctor')->unsigned()->nullable()->index();

            $table->timestamps();
           // $table->foreign('ipd_id')->references('id')->on('ipd_details')->onDelete('cascade');
            // $table->foreign('cons_doctor')->references('id')->on('doctors')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultant_register');
    }
};
