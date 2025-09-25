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
        Schema::create('system_notification', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
                 $table->string('notification_title', 200)->index(); // notification_title
            $table->string('notification_type', 50); // notification_type
            $table->text('notification_desc')->nullable(); // notification_desc
            $table->string('notification_for', 50); // notification_for
            $table->unsignedBigInteger('role_id')->nullable(); // role_id
            $table->unsignedBigInteger('receiver_id')->nullable(); // receiver_id
            $table->dateTime('date'); // date
            $table->string('is_active', 10); // is_active
            $table->timestamp('created_at')->useCurrent(); // created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_notification');
    }
};
