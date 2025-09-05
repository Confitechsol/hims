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
        Schema::create('sch_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('base_url', 500)->nullable();
            $table->text('folder_path')->nullable();
            $table->string('name', 100)->nullable();
            $table->integer('biometric')->default(0)->nullable();
            $table->text('biometric_device')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('start_month', 100);
            $table->unsignedBigInteger('session_id')->nullable()->index();
            $table->unsignedBigInteger('lang_id')->nullable()->index();
            $table->string('languages', 255)->default('["4"]');
            $table->string('dise_code', 50)->nullable();
            $table->string('date_format', 50);
            $table->string('time_format', 20)->default('24-hour')->nullable();
            $table->string('currency', 50);
            $table->string('currency_symbol', 50);
            $table->string('is_rtl', 10)->default('disabled')->nullable();
            $table->string('timezone', 30)->default('UTC')->nullable();
            $table->string('image', 100)->nullable();
            $table->string('mini_logo', 200);
            $table->string('theme', 200)->default('default.jpg');
            $table->string('credit_limit', 255)->nullable();
            $table->string('opd_record_month', 50)->nullable();
            $table->string('is_active', 255)->default('no')->nullable();
            $table->string('cron_secret_key', 100);
            $table->string('doctor_restriction', 100);
            $table->string('superadmin_restriction', 200);
            $table->string('patient_panel', 50);
            $table->string('scan_code_type', 50)->default('barcode');
            $table->string('mobile_api_url', 200);
            $table->string('app_primary_color_code', 50);
            $table->string('app_secondary_color_code', 50);
            $table->string('app_logo', 200);
            $table->string('zoom_api_key', 200);
            $table->string('zoom_api_secret', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sch_settings');
    }
};
