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
        Schema::create('content_for', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('role', 50)->nullable();
            // VARCHAR(50), NULL

            $table->integer('content_id')->unsigned()->nullable()->index();
            // INT(11), NULL, with index

            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->timestamps();
            //$table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            //$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_for');
    }
};
