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
        Schema::create('userlog', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('user', 100)->nullable(); // user varchar(100) NULL
            $table->string('role', 100)->nullable(); // role varchar(100) NULL
            $table->string('ipaddress', 100)->nullable(); // ipaddress varchar(100) NULL
            $table->string('user_agent', 500)->nullable(); // user_agent varchar(500) NULL
            $table->timestamp('login_datetime')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userlog');
    }
};
