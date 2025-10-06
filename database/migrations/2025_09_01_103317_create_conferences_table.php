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
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('purpose', 200);                                      // purpose varchar(200) NOT NULL
            $table->unsignedBigInteger('staff_id')->nullable()->index();         // staff_id int(11) NULL
            $table->unsignedBigInteger('patient_id')->nullable()->index();       // patient_id int(11) NULL
            $table->unsignedBigInteger('visit_details_id')->nullable()->index(); // visit_details_id int(11) NULL
            $table->unsignedBigInteger('ipd_id')->nullable()->index();           // ipd_id int(11) NULL
            $table->unsignedBigInteger('created_id')->nullable()->index();       // created_id int(11) NULL
            $table->text('title')->nullable();                                   // title TEXT NULL
            $table->dateTime('date');                                            // date DATETIME NOT NULL
            $table->integer('duration');                                         // duration int(11) NOT NULL
            $table->string('password', 100);                                     // password varchar(100) NOT NULL
            $table->integer('host_video');                                       // host_video int(11) NOT NULL
            $table->integer('client_video');                                     // client_video int(11) NOT NULL
            $table->mediumText('description')->nullable();                       // description mediumtext NULL
            $table->text('timezone')->nullable();                                // timezone TEXT NULL
            $table->text('return_response')->nullable();                         // return_response TEXT NULL
            $table->string('api_type', 50);                                      // api_type varchar(50) NOT NULL
            $table->integer('status');                                           // status int(11) NOT NULL
            $table->integer('live_consult_link')->default(1);                    // live_consult_link int(11) NOT NULL DEFAULT 1
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferences');
    }
};
