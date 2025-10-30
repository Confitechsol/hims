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
        Schema::create('permission_group', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
             $table->string('name', 100)->nullable()->index();
            $table->string('short_code', 100)->index();

            $table->integer('is_active')->default(0);
            $table->integer('system'); // no default given in your schema

            $table->decimal('sort_order', 10, 2);

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_group');
    }
};
