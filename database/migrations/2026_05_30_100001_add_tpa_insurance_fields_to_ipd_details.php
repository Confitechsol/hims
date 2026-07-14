<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            if (!Schema::hasColumn('ipd_details', 'insurance_company_id')) {
                $table->unsignedBigInteger('insurance_company_id')->nullable()->after('organisation_id')->index();
                $table->foreign('insurance_company_id')
                    ->references('id')->on('insurance_companies')->nullOnDelete();
            }
            if (!Schema::hasColumn('ipd_details', 'is_cashless')) {
                $table->boolean('is_cashless')->default(false)->after('insurance_company_id');
            }
            if (!Schema::hasColumn('ipd_details', 'insurance_policy_no')) {
                $table->string('insurance_policy_no', 100)->nullable()->after('is_cashless');
            }
            if (!Schema::hasColumn('ipd_details', 'insurance_card_no')) {
                $table->string('insurance_card_no', 100)->nullable()->after('insurance_policy_no');
            }
            if (!Schema::hasColumn('ipd_details', 'ccn_no')) {
                $table->string('ccn_no', 100)->nullable()->after('insurance_card_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            if (Schema::hasColumn('ipd_details', 'insurance_company_id')) {
                $table->dropForeign(['insurance_company_id']);
                $table->dropColumn('insurance_company_id');
            }
            foreach (['is_cashless', 'insurance_policy_no', 'insurance_card_no', 'ccn_no'] as $col) {
                if (Schema::hasColumn('ipd_details', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
