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
        Schema::create('dispatch_receive', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('reference_no', 50)->index();

            $table->string('to_title', 100)->index();

            $table->text('address')->nullable();

            $table->text('note')->nullable();

            $table->string('from_title', 200);

            $table->date('date')->nullable()->index();

            $table->string('image', 100);

            $table->string('type', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_receive');
    }
};