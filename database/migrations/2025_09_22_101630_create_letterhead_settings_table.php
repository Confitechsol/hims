<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('letterhead_settings', function (Blueprint $table) {
            $table->id();
            // $table->binary('print_header')->nullable();
            $table->text('print_footer')->nullable();
            $table->unsignedBigInteger('letterhead_cat_id');

            $table->string('setting_for', 200);
            $table->string('is_active', 50)->default('yes');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE letterhead_settings ADD print_header LONGBLOB NULL AFTER id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letterhead_settings');
    }
};