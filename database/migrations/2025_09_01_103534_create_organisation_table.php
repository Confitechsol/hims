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
        Schema::create('organisation', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('organisation_name', 200)->index();
            $table->string('code', 50)->index();
            $table->string('contact_no', 20)->index();
            $table->string('address', 300)->index();
            $table->string('contact_person_name', 200)->index();
            $table->string('contact_person_phone', 20)->index();
            $table->string('poilicy_no', 200)->index();
            $table->string('e_card_no', 200)->index();
            $table->string('e_card_upload', 200)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisation');
    }
};