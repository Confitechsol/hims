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
        Schema::create('send_notification', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('title', 50)->nullable()->index();
            $table->date('publish_date')->nullable()->index();
            $table->date('date')->nullable()->index();
            $table->text('message')->nullable();
            $table->string('visible_staff', 10)->default('no')->index();
            $table->string('visible_patient', 10)->default('no')->index();
            $table->string('created_by', 60)->nullable();
            $table->unsignedBigInteger('created_id')->nullable()->index();
            $table->string('is_active', 10)->default('no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('send_notification');
    }
};
