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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('name', 100)->nullable()->index();
            // VARCHAR(100), NULL, indexed

            $table->string('belong_to', 100)->nullable()->index();
            // VARCHAR(100), NULL, indexed

            $table->string('type', 100)->nullable()->index();
            // VARCHAR(100), NULL, indexed

            $table->integer('bs_column')->nullable();
            // INT(11), NULL

            $table->integer('validation')->default(0);
            // INT(11), default 0

            $table->mediumText('field_values')->nullable();
            // MEDIUMTEXT, NULL

            $table->integer('visible_on_print')->nullable()->index();
            // INT(11), NULL, indexed

            $table->integer('visible_on_report')->nullable()->index();
            // INT(11), NULL, indexed

            $table->integer('visible_on_table')->nullable()->index();
            // INT(11), NULL, indexed

            $table->integer('visible_on_patient_panel')->nullable();
            // INT(11), NULL

            $table->integer('weight')->nullable();
            // INT(11), NULL

            $table->integer('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
