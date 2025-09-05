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
        Schema::create('referral_person', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('name', 100)->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->string('contact', 20)->nullable()->index();
            $table->string('person_name', 100)->nullable()->index();
            $table->string('person_phone', 50)->nullable()->index();
            $table->float('standard_commission', 10, 2)->default(0.00)->index();
            $table->string('address', 100)->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_person');
    }
};
