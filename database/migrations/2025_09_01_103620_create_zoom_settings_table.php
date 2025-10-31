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
        Schema::create('zoom_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('zoom_api_key', 200)->nullable();
            $table->string('zoom_api_secret', 200)->nullable();

            $table->integer('use_doctor_api')->default(1)->nullable();
            $table->integer('use_zoom_app')->default(1)->nullable();

            $table->integer('opd_duration')->nullable();
            $table->integer('ipd_duration')->nullable();

            $table->timestamp('created_at')
                  ->useCurrent()
                  ->useCurrentOnUpdate(); // current_timestamp() ON UPDATE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_settings');
    }
};
