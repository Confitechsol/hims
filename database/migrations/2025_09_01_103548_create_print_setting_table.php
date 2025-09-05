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
        Schema::create('print_setting', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->text('print_header');
            $table->text('print_footer');
            $table->string('setting_for', 200);
            $table->string('is_active', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_setting');
    }
};