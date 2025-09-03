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
        Schema::create('payslip_allowance', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            
            $table->unsignedBigInteger('staff_payslip_id')->nullable()->index();
            $table->unsignedBigInteger('staff_id')->nullable()->index();

            $table->string('allowance_type', 200)->index();
            $table->float('amount')->index();
            $table->string('cal_type', 100)->index();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslip_allowance');
    }
};
