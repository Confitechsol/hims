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
        Schema::create('nurse_notes_comment', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('nurse_note_id')->nullable()->index();
            $table->unsignedBigInteger('comment_staffid')->nullable()->index();
            $table->text('comment_staff')->nullable();
            $table->timestamps();
            $table->foreign('nurse_note_id')->references('id')->on('nurse_note')->onDelete('cascade');
            $table->foreign('comment_staffid')->references('id')->on('staff')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurse_notes_comment');
    }
};