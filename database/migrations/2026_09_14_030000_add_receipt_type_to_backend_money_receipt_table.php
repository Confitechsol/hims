<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('backend_money_receipt') && !Schema::hasColumn('backend_money_receipt', 'receipt_type')) {
            Schema::table('backend_money_receipt', function (Blueprint $table) {
                $table->string('receipt_type', 50)->default('Backend Bill Receipt')->after('receipt_no');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('backend_money_receipt') && Schema::hasColumn('backend_money_receipt', 'receipt_type')) {
            Schema::table('backend_money_receipt', function (Blueprint $table) {
                $table->dropColumn('receipt_type');
            });
        }
    }
};