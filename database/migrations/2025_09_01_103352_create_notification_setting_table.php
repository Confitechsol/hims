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
        Schema::create('notification_setting', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('type', 100)->nullable()->index(); // notification type
            $table->integer('is_mail')->nullable()->default(0)->index();
            $table->integer('is_sms')->nullable()->default(0)->index();
            $table->integer('is_mobileapp')->nullable()->index();
            $table->integer('is_notification')->nullable()->index();
            $table->integer('display_notification')->nullable()->index();
            $table->integer('display_sms')->nullable()->index();

            $table->longText('template')->nullable();
            $table->string('template_id', 100); // required
            $table->text('subject')->nullable();
            $table->text('variables')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_setting');
    }
};