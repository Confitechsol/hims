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
        Schema::create('permission_category', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
              $table->unsignedBigInteger('perm_group_id')->nullable();

            $table->string('name', 100)->nullable()->index();
            $table->string('short_code', 100)->nullable();

            $table->integer('enable_view')->default(0);
            $table->integer('enable_add')->default(0);
            $table->integer('enable_edit')->default(0);
            $table->integer('enable_delete')->default(0);

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_category');
    }
};
