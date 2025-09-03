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
        Schema::create('front_cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('page_type', 10)->default('manual');
            // VARCHAR(10), NOT NULL, default 'manual'

            $table->integer('is_homepage')->nullable()->default(0);
            // INT(11), NULL, default 0

            $table->string('title', 250)->nullable();
            // VARCHAR(250), NULL

            $table->string('url', 250)->nullable();
            // VARCHAR(250), NULL

            $table->string('type', 50)->nullable();
            // VARCHAR(50), NULL

            $table->string('slug', 200)->nullable();
            // VARCHAR(200), NULL

            $table->mediumText('meta_title')->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->mediumText('meta_keyword')->nullable();
            // MEDIUMTEXT, NULL

            $table->string('feature_image', 200);
            // VARCHAR(200), NOT NULL

            $table->longText('description')->nullable();
            // LONGTEXT, NULL

            $table->date('publish_date')->nullable();
            // DATE, NULL

            $table->integer('publish')->nullable()->default(0);
            // INT(11), NULL, default 0

            $table->integer('sidebar')->nullable()->default(0);
            // INT(11), NULL, default 0

            $table->string('is_active', 10)->nullable()->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_cms_pages');
    }
};