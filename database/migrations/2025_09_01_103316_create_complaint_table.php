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
        Schema::create('complaint', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('complaint_type_id')->nullable()->index(); // complaint_type_id int(11) NULL
            $table->string('source', 100);                                        // source varchar(100) NOT NULL
            $table->string('name', 100);                                          // name varchar(100) NOT NULL
            $table->string('contact', 20);                                        // contact varchar(20) NOT NULL
            $table->string('email', 200);                                         // email varchar(200) NOT NULL
            $table->date('date')->nullable();                                     // date DATE NULL
            $table->text('description')->nullable();                              // description TEXT NULL
            $table->string('action_taken', 200);                                  // action_taken varchar(200) NOT NULL
            $table->string('assigned', 50);                                       // assigned varchar(50) NOT NULL
            $table->text('note')->nullable();                                     // note TEXT NULL
            $table->text('image');                                                // image TEXT NOT NULL
            $table->timestamp('created_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint');
    }
};
