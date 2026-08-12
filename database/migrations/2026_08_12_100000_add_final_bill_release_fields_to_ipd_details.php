<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            if (! Schema::hasColumn('ipd_details', 'final_bill_generated_at')) {
                $table->timestamp('final_bill_generated_at')->nullable()->after('discharged_date');
            }
            if (! Schema::hasColumn('ipd_details', 'final_bill_generated_by')) {
                $table->unsignedBigInteger('final_bill_generated_by')->nullable()->after('final_bill_generated_at');
            }
            if (! Schema::hasColumn('ipd_details', 'include_post_discharge_bed_charge')) {
                $table->boolean('include_post_discharge_bed_charge')->nullable()->after('final_bill_generated_by');
            }
            if (! Schema::hasColumn('ipd_details', 'physical_release_at')) {
                $table->timestamp('physical_release_at')->nullable()->after('include_post_discharge_bed_charge');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            foreach ([
                'final_bill_generated_at',
                'final_bill_generated_by',
                'include_post_discharge_bed_charge',
                'physical_release_at',
            ] as $column) {
                if (Schema::hasColumn('ipd_details', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
