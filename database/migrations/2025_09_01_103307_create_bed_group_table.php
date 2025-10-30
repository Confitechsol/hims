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
        Schema::create('bed_group', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('name', 200)->index();
            $table->string('color', 50)->default('#f4f4f4')->index();
            $table->text('description');
            $table->integer('floor');
            $table->decimal('bed_cost', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(1); // active/inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bed_group');
    }
};
