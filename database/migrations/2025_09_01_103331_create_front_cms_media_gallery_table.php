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
        Schema::create('front_cms_media_gallery', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('image', 300)->nullable();
            // VARCHAR(300), NULL

            $table->string('thumb_path', 300)->nullable();
            // VARCHAR(300), NULL

            $table->string('dir_path', 300)->nullable();
            // VARCHAR(300), NULL

            $table->string('img_name', 300)->nullable();
            // VARCHAR(300), NULL

            $table->string('thumb_name', 300)->nullable();
            // VARCHAR(300), NULL

            $table->string('file_type', 100);
            // VARCHAR(100), NOT NULL

            $table->string('file_size', 100);
            // VARCHAR(100), NOT NULL

            $table->mediumText('vid_url')->nullable();
            // MEDIUMTEXT, NULL

            $table->string('vid_title', 250);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_cms_media_gallery');
    }
};