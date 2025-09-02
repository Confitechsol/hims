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
        Schema::create('charge_type_module', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('charge_type_master_id')->nullable()->index(); // charge_type_master_id
            $table->string('module_shortcode', 50)->nullable();                       // module_shortcode
            $table->timestamp('created_at')->useCurrent();                            // created_at with default current_timestamp

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_type_module');
    }
};
