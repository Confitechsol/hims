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
        Schema::create('users_authentication', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('users_id')->nullable(); // users_id int(11) nullable
            $table->string('token', 200); // token varchar(200)
            $table->dateTime('expired_at'); // expired_at datetime

            $table->timestamp('created_at')
                  ->useCurrent()
                  ->useCurrentOnUpdate(); // created_at auto timestamp

            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_authentication');
    }
};
