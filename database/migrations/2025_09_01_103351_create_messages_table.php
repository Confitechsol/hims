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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('title', 200)->nullable()->index();          // title (nullable, indexed)
            $table->string('template_id', 100);                         // template id (required)
            $table->text('message')->nullable();                        // message body
            $table->string('send_mail', 10)->default('0')->index();     // send mail flag
            $table->string('send_sms', 10)->default('0')->index();      // send sms flag
            $table->string('is_group', 10)->default('0')->index();      // is group flag
            $table->string('is_individual', 10)->default('0')->index(); // is individual flag
            $table->string('file', 200);                                // file name (required)
            $table->text('group_list')->nullable();                     // list of groups
            $table->text('user_list')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};