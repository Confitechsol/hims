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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->text('message')->nullable();                       // message text NULL
            $table->unsignedBigInteger('chat_user_id')->index();       // chat_user_id int(11) NOT NULL
            $table->string('ip', 30);                                  // ip varchar(30) NOT NULL
            $table->integer('time');                                   // time int(11) NOT NULL
            $table->integer('is_first')->default(0);                   // is_first int(11) DEFAULT 0
            $table->integer('is_read')->default(0);                    // is_read int(11) NOT NULL DEFAULT 0
            $table->unsignedBigInteger('chat_connection_id')->index(); // chat_connection_id int(11) NOT NULL
            $table->dateTime('created_at')->nullable();                // created_at datetime NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
