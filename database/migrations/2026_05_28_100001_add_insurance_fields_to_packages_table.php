<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('package_type', 20)->default('hospital')->after('branch_id')->index();
            $table->unsignedBigInteger('insurance_company_id')->nullable()->after('package_type')->index();
            $table->unsignedBigInteger('insurance_rate_panel_id')->nullable()->after('insurance_company_id')->index();
            $table->string('insurer_procedure_code', 50)->nullable()->after('name')->index();
            $table->string('speciality', 100)->nullable()->after('insurer_procedure_code');
            $table->string('package_inclusions', 50)->nullable()->after('speciality');
            $table->string('package_exclusions', 50)->nullable()->after('package_inclusions');
            $table->text('inclusion_notes')->nullable()->after('description');
            $table->date('effective_from')->nullable()->after('inclusion_notes');
            $table->date('effective_to')->nullable()->after('effective_from');
            $table->string('contract_reference', 200)->nullable()->after('effective_to');

            $table->foreign('insurance_company_id')
                ->references('id')->on('insurance_companies')->nullOnDelete();
            $table->foreign('insurance_rate_panel_id')
                ->references('id')->on('insurance_rate_panels')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['insurance_company_id']);
            $table->dropForeign(['insurance_rate_panel_id']);
            $table->dropColumn([
                'package_type',
                'insurance_company_id',
                'insurance_rate_panel_id',
                'insurer_procedure_code',
                'speciality',
                'package_inclusions',
                'package_exclusions',
                'inclusion_notes',
                'effective_from',
                'effective_to',
                'contract_reference',
            ]);
        });
    }
};
