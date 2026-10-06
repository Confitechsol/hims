<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            if (! Schema::hasColumn('ipd_details', 'final_discharge_at')) {
                $table->timestamp('final_discharge_at')->nullable()->after('final_bill_generated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            if (Schema::hasColumn('ipd_details', 'final_discharge_at')) {
                $table->dropColumn('final_discharge_at');
            }
        });
    }
};
