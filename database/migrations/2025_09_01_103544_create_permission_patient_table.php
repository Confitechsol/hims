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
        Schema::create('permission_patient', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('permission_group_short_code', 100)->index();
            $table->string('name', 100)->index();
            $table->string('short_code', 100)->index();

            $table->integer('is_active')->nullable()->index();
            $table->integer('system'); // required (NO NULL)
            $table->decimal('sort_order', 10, 2); // required (NO NULL)

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_patient');
    }
};
