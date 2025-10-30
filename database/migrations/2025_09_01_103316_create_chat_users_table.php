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
        Schema::create('chat_users', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('user_type', 20)->nullable();                          // user_type varchar(20) NULL
            $table->unsignedBigInteger('staff_id')->nullable()->index();          // staff_id int(11) NULL
            $table->unsignedBigInteger('patient_id')->nullable()->index();        // patient_id int(11) NULL
            $table->unsignedBigInteger('create_staff_id')->nullable()->index();   // create_staff_id int(11) NULL
            $table->unsignedBigInteger('create_patient_id')->nullable()->index(); // create_patient_id int(11) NULL
            $table->integer('is_active')->default(0);                             // is_active int(11) default 0
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();  // created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->date('updated_at')->nullable();                               // updated_at DATE NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_users');
    }
};
