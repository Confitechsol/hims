<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            if (!Schema::hasColumn('ipd_details', 'final_approval_amount')) {
                $table->decimal('final_approval_amount', 12, 2)->nullable()->after('initial_approval_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            if (Schema::hasColumn('ipd_details', 'final_approval_amount')) {
                $table->dropColumn('final_approval_amount');
            }
        });
    }
};
