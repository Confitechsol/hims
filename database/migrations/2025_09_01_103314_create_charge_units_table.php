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
        Schema::create('charge_units', function (Blueprint $table) {

            $table->id(); // id int(11) auto_increment primary key
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('unit', 50)->nullable()->index();      // unit varchar(50) NULL with index
            $table->integer('is_active')->default(0)->nullable(); // is_active int(11) default 0
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_units');
    }
};
