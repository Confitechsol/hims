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
        Schema::create('front_cms_menus', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('menu', 100)->nullable();

            $table->string('slug', 200)->nullable();

            $table->mediumText('description')->nullable();

            $table->integer('open_new_tab')->default(0);

            $table->mediumText('ext_url')->nullable();

            $table->mediumText('ext_url_link')->nullable();

            $table->integer('publish')->default(0);

            $table->string('content_type', 10)->default('manual');

            $table->string('is_active', 10)->default('no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_cms_menus');
    }
};