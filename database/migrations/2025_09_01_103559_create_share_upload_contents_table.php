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
        Schema::create('share_upload_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('upload_content_id')->nullable()->index();
            $table->unsignedBigInteger('share_content_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('upload_content_id')->references('id')->on('upload_contents')->onDelete('cascade');
            $table->foreign('share_content_id')->references('id')->on('share_contents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_upload_contents');
    }
};
