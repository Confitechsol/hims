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
        Schema::create('email_config', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('email_type', 100)->nullable();

            $table->string('smtp_server', 100)->nullable();

            $table->string('smtp_port', 100)->nullable();

            $table->string('smtp_username', 100)->nullable();

            $table->string('smtp_password', 100)->nullable();

            $table->string('ssl_tls', 100)->nullable();

            $table->string('smtp_auth', 10);

            $table->string('is_active', 10)->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_config');
    }
};