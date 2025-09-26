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
        Schema::create('bed_type', function (Blueprint $table) {

            $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('name', 100)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bed_type');
    }
};
