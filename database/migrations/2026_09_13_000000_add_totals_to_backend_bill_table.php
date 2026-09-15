<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backend_bill', function (Blueprint $table) {
            $table->decimal('total_amount', 12, 2)->default(0)->after('doctor_name');
            $table->enum('adjustment_type', ['add', 'discount'])->default('add')->after('total_amount');
            $table->decimal('adjustment_amount', 12, 2)->default(0)->after('adjustment_type');
        });
    }

    public function down(): void
    {
        Schema::table('backend_bill', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'adjustment_type', 'adjustment_amount']);
        });
    }
};