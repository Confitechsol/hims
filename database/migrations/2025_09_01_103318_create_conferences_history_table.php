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
        Schema::create('conferences_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conference_id')->nullable()->index(); // conference_id int(11) NULL
            $table->unsignedBigInteger('staff_id')->nullable()->index(); // staff_id int(11) NULL
            $table->unsignedBigInteger('patient_id')->nullable()->index(); // patient_id int(11) NULL
            $table->integer('total_hit'); // total_hit int(11) NOT NULL
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferences_history');
    }
};
