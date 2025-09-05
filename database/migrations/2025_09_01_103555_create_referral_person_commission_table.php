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
        Schema::create('referral_person_commission', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('referral_person_id')->nullable()->index();
            $table->unsignedBigInteger('referral_type_id')->nullable()->index();
            $table->float('commission', 10, 2)->default(0.00);
            $table->timestamps();
            $table->foreign('referral_person_id')->references('id')->on('referral_person')->onDelete('cascade');
            $table->foreign('referral_type_id')->references('id')->on('referral_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_person_commission');
    }
};
