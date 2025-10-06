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
        Schema::create('front_cms_programs', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('type', 50)->nullable();
            $table->string('slug', 255)->nullable();
            $table->mediumText('url')->nullable();
            $table->string('title', 200)->nullable();
            $table->date('date')->nullable();
            $table->date('event_start')->nullable();
            $table->date('event_end')->nullable();
            $table->mediumText('event_venue')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('is_active', 10)->default('no');
            $table->mediumText('meta_title')->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->mediumText('meta_keyword')->nullable();
            $table->text('feature_image')->nullable();
            $table->date('publish_date')->nullable();
            $table->string('publish', 10)->default('0');
            $table->integer('sidebar')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_cms_programs');
    }
};