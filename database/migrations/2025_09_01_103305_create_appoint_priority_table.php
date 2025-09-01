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
        Schema::create('appoint_priority', function (Blueprint $table) {
            $table->id();
            $table->string('appoint_priority', 100);

            $table->timestamp('created_at')
                  ->useCurrent()
                  ->useCurrentOnUpdate();

            // 🔹 Add index since schema had MUL
            $table->index('appoint_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appoint_priority');
    }
};
