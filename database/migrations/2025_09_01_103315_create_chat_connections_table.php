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
        Schema::create('chat_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_user_one')->index(); // chat_user_one int(11) NOT NULL
            $table->unsignedBigInteger('chat_user_two')->index(); // chat_user_two int(11) NOT NULL
            $table->string('ip', 30)->nullable(); // ip varchar(30) NULL
            $table->integer('time')->nullable(); // time int(11) NULL
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate(); // created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_connections');
    }
};
