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
        Schema::create('item_supplier', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('item_supplier', 255)->index();
            $table->string('phone', 255)->index();
            $table->string('email', 255)->index();
            $table->string('address', 255)->index();
            $table->string('contact_person_name', 255)->index();
            $table->string('contact_person_phone', 255)->index();
            $table->string('contact_person_email', 255)->index();
            $table->string('is_active', 20)->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_supplier');
    }
};