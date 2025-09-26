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
        Schema::create('medicine_supplier', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('supplier', 200)->index();                // supplier name
            $table->string('contact', 200)->index();                 // supplier contact
            $table->string('supplier_person', 200)->index();         // contact person
            $table->string('supplier_person_contact', 200)->index(); // contact person number
            $table->string('supplier_drug_licence', 200)->index();   // license number
            $table->string('address', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_supplier');
    }
};