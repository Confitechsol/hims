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
        Schema::create('front_cms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('theme', 50)->nullable();
            $table->integer('is_active_rtl')->default(0);
            $table->integer('is_active_front_cms')->default(0);
            $table->integer('is_active_online_appointment')->nullable();
            $table->integer('is_active_sidebar')->default(0);

            $table->text('logo')->nullable();
            $table->string('contact_us_email', 100)->nullable();
            $table->string('complain_form_email', 100)->nullable();
            $table->mediumText('sidebar_options')->nullable();

            $table->string('fb_url', 200)->nullable();
            $table->string('twitter_url', 200)->nullable();
            $table->string('youtube_url', 200)->nullable();
            $table->string('google_plus', 200)->nullable();
            $table->string('instagram_url', 200)->nullable();
            $table->string('pinterest_url', 200)->nullable();
            $table->string('linkedin_url', 200)->nullable();

            $table->mediumText('google_analytics')->nullable();
            $table->string('footer_text', 500)->nullable();
            $table->string('fav_icon', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_cms_settings');
    }
};