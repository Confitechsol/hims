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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_name', 100);
            $table->text('certificate_text')->nullable();
            $table->string('left_header', 100);
            $table->string('center_header', 100);
            $table->string('right_header', 100);
            $table->string('left_footer', 100);
            $table->string('right_footer', 100);
            $table->string('center_footer', 100);
            $table->text('background_image');
            $table->boolean('created_for');
            $table->boolean('status');
            $table->integer('header_height');
            $table->integer('content_height');
            $table->integer('footer_height');
            $table->integer('content_width');
            $table->boolean('enable_patient_image');
            $table->integer('enable_image_height');
            $table->date('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
