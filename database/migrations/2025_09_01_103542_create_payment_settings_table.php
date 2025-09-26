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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
             $table->string('payment_type', 200)->index();
            $table->string('api_username', 200)->nullable()->index();
            $table->string('api_secret_key', 200)->index();
            $table->string('salt', 200)->index();
            $table->string('api_publishable_key', 200)->index();
            $table->string('paytm_website', 255)->index();
            $table->string('paytm_industrytype', 255)->index();
            $table->string('api_password', 200)->nullable()->index();
            $table->string('api_signature', 200)->nullable()->index();
            $table->string('api_email', 200)->nullable()->index();
            $table->string('paypal_demo', 100)->index();
            $table->string('account_no', 200)->index();
            $table->string('is_active', 255)->nullable()->default('no')->index();

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
