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
        Schema::table('ipd_details', function (Blueprint $table) {
            $table->decimal('mou_discount', 12, 2)->default(0)->after('amount');
            $table->decimal('special_discount', 12, 2)->default(0)->after('mou_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            $table->dropColumn(['mou_discount', 'special_discount']);
        });
    }
};
