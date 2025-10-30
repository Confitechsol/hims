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
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('role_id')->nullable()->index();
            $table->unsignedBigInteger('perm_cat_id')->nullable()->index();
            $table->integer('can_view')->nullable();
            $table->integer('can_add')->nullable();
            $table->integer('can_edit')->nullable();
            $table->integer('can_delete')->nullable();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('perm_cat_id')->references('id')->on('permission_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles_permissions');
    }
};
