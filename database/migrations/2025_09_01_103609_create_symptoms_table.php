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
        Schema::create('symptoms', function (Blueprint $table) {
            $table->id(); // Primary key, auto-increment
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('symptoms_title', 200)->index(); // Indexed, not null
            $table->text('description')->nullable(); // Nullable text field
            $table->string('type', 100); // Not null
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate(); // Default current timestamp, updates on modification
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptoms');
    }
};
