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
        Schema::create('visitors_book', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
                        $table->string('source', 100)->nullable()->index(); // varchar(100) NULL
            $table->string('purpose', 100)->index(); // varchar(100) NOT NULL
            $table->string('name', 100)->index(); // varchar(100) NOT NULL
            $table->string('email', 100)->nullable()->index(); // varchar(100) NULL
            $table->string('contact', 12)->index(); // varchar(12) NOT NULL
            $table->string('id_proof', 50)->index(); // varchar(50) NOT NULL
            $table->string('visit_to', 20)->index(); // varchar(20) NOT NULL
            $table->unsignedInteger('ipd_opd_staff_id')->nullable(); // int(11) NULL
            $table->string('related_to', 60)->index(); // varchar(60) NOT NULL
            $table->integer('no_of_pepple')->index(); // int(11) NOT NULL
            $table->date('date')->index(); // date NOT NULL
            $table->string('in_time', 20)->index(); // varchar(20) NOT NULL
            $table->string('out_time', 20)->index(); // varchar(20) NOT NULL
            $table->mediumText('note')->nullable(); // mediumtext NULL
            $table->text('image'); // text NOT NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors_book');
    }
};
