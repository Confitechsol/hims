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
        Schema::create('system_notification_setting', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('event', 100)->index(); // event
            $table->string('subject', 255); // subject
            $table->text('staff_message')->nullable(); // staff_message
            $table->integer('is_staff')->default(1)->index(); // is_staff
            $table->text('patient_message')->nullable(); // patient_message
            $table->integer('is_patient')->default(0)->index(); // is_patient
            $table->text('variables')->nullable(); // variables
            $table->string('url', 255); // url
            $table->string('patient_url', 255); // patient_url
            $table->string('notification_type', 255); // notification_type
            $table->integer('is_active')->default(1); // is_active
            $table->timestamp('created_at')->useCurrent(); // created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_notification_setting');
    }
};
