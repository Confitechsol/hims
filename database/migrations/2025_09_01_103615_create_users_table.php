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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->integer('user_id')->nullable();                      // user_id int(11) NULL
            $table->string('username', 50)->nullable();                  // username varchar(50) NULL
            $table->string('email', 255)->nullable();                    // username varchar(50) NULL
            $table->string('password', 50)->nullable();                  // password varchar(50) NULL
            $table->text('childs')->nullable();                          // childs text NULL
            $table->unsignedBigInteger('role');                          // role varchar(30) NOT NULL
            $table->string('verification_code', 200);                    // verification_code varchar(200) NOT NULL
            $table->string('is_active', 10)->nullable()->default('yes'); // is_active varchar(10) NULL default 'yes'
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
