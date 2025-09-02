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
        Schema::create('blood_bank_products', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('name', 255)->index();                      // name varchar(255) NO MUL
            $table->integer('is_blood_group')->default(1)->nullable(); // int(11), default 1
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_bank_products');
    }
};
