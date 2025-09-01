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
        Schema::create('charge_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('charge_type_id')->nullable()->index(); // charge_type_id
            $table->string('name', 200)->index(); // name
            $table->mediumText('description')->nullable(); // description
            $table->string('short_code', 30)->nullable(); // short_code
            $table->string('is_default', 10); // is_default
            $table->timestamp('created_at')->nullable()->useCurrent()->useCurrentOnUpdate(); // created_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_categories');
    }
};
