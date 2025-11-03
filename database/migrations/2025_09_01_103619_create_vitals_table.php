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
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('name', 100)->index(); // name varchar(100) NOT NULL
            $table->string('reference_range', 100)->index(); // reference_range varchar(100) NOT NULL
            $table->string('unit', 11)->nullable()->index(); // unit varchar(11) NULL
            $table->integer('is_system')->default(0)->index(); // is_system int(11) NOT NULL default 0
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vitals');
    }
};
