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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('vehicle_no', 20)->nullable()->index();
            $table->string('vehicle_model', 100)->index();
            $table->string('manufacture_year', 4)->nullable()->index();
            $table->string('vehicle_type', 100)->index();

            $table->string('driver_name', 50)->nullable()->index();
            $table->string('driver_licence', 50)->index();
            $table->string('driver_contact', 20)->nullable()->index();

            $table->text('note')->nullable();

            $table->timestamp('created_at')
                  ->useCurrent()
                  ->useCurrentOnUpdate(); // auto update on change
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
