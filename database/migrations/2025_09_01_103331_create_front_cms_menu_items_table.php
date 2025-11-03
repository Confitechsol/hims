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
        Schema::create('front_cms_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('menu_id')->nullable();

            $table->string('menu', 100)->nullable();

            $table->unsignedBigInteger('page_id');

            $table->unsignedBigInteger('parent_id');

            $table->mediumText('ext_url')->nullable();

            $table->integer('open_new_tab')->default(0)->nullable();

            $table->mediumText('ext_url_link')->nullable();

            $table->string('slug', 200)->nullable();

            $table->integer('weight')->nullable();

            $table->integer('publish')->default(0);

            $table->mediumText('description')->nullable();

            $table->string('is_active', 10)->default('no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_cms_menu_items');
    }
};