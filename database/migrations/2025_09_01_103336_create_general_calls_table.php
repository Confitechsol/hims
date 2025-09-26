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
        Schema::create('general_calls', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('name', 100)->index();
            $table->string('contact', 12)->index();
            $table->date('date')->index();
            $table->text('description')->nullable();
            $table->date('follow_up_date')->nullable()->index();
            $table->string('call_duration', 50)->index();
            $table->mediumText('note')->nullable();
            $table->string('call_type', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_calls');
    }
};