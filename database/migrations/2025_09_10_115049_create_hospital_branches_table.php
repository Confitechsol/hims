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
        Schema::create('hospital_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospital_id'); // parent hospital

            $table->string('name', 150);                // branch name
            $table->string('branch_id', 50)->unique(); // unique branch code
            $table->string('email', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->text('address')->nullable();

            $table->string('timezone', 30)->nullable()->default('UTC');
            $table->string('image', 100)->nullable();
            $table->string('mini_logo', 200)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Foreign key to hospital
            $table->foreign('hospital_id')
                  ->references('id')
                  ->on('hospital')   // 
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_branches');
    }
};
