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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('charge_category_id')->nullable()->index(); // charge_category_id int(11) NULL
            $table->unsignedBigInteger('tax_category_id')->nullable()->index(); // tax_category_id int(11) NULL
            $table->unsignedBigInteger('charge_unit_id')->nullable()->index(); // charge_unit_id int(11) NULL
            $table->string('name', 200)->index(); // name varchar(200) NOT NULL
            $table->float('standard_charge', 10, 2)->default(0.00)->nullable()->index(); // standard_charge float(10,2) DEFAULT 0.00
            $table->date('date')->nullable()->index(); // date DATE NULL
            $table->mediumText('description')->nullable(); // description mediumtext NULL
            $table->string('status', 100); // status varchar(100) NOT NULL
            $table->timestamp('created_at')->useCurrent(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};
