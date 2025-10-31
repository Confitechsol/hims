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
        Schema::create('blood_donor', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('donor_name', 100)->nullable()->index(); // donor_name varchar(100) YES MUL
            $table->date('date_of_birth')->nullable();              // date_of_birth date YES

            $table->unsignedBigInteger('blood_bank_product_id')->nullable()->index(); // blood_bank_product_id int(11) YES MUL

            $table->string('gender', 11)->nullable()->index();       // gender varchar(11) YES MUL
            $table->string('father_name', 100)->nullable()->index(); // father_name varchar(100) YES MUL
            $table->text('address')->nullable();                     // address text YES
            $table->string('contact_no', 20)->nullable()->index();   // contact_no varchar(20) YES MUL

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_donor');
    }
};
