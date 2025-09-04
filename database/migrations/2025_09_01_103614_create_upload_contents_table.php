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
        Schema::create('upload_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
             $table->unsignedBigInteger('content_type_id'); // content_type_id int(11) not null
            $table->text('image')->nullable(); // image text
            $table->text('thumb_path')->nullable(); // thumb_path text
            $table->string('dir_path', 300)->nullable(); // dir_path varchar(300)
            $table->text('real_name'); // real_name text not null
            $table->text('img_name')->nullable(); // img_name text
            $table->string('thumb_name', 300)->nullable(); // thumb_name varchar(300)
            $table->string('file_type', 100); // file_type varchar(100) not null
            $table->text('mime_type'); // mime_type text not null
            $table->string('file_size', 100); // file_size varchar(100) not null
            $table->text('vid_url'); // vid_url text not null
            $table->string('vid_title', 250); // vid_title varchar(250) not null
            $table->unsignedBigInteger('upload_by'); // upload_by int(11) not null
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_contents');
    }
};
