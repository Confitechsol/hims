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
        Schema::create('charge_type_master', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('charge_type', 200)->index();   // charge_type
            $table->string('is_default', 10);              // is_default
            $table->string('is_active', 10);               // is_active
            $table->timestamp('created_at')->useCurrent(); // created_at with current_timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_type_master');
    }
};
