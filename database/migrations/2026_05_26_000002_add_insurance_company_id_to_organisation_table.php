<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisation', function (Blueprint $table) {
            $table->unsignedBigInteger('insurance_company_id')->nullable()->after('branch_id')->index();
            $table->foreign('insurance_company_id')
                ->references('id')
                ->on('insurance_companies')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('organisation', function (Blueprint $table) {
            $table->dropForeign(['insurance_company_id']);
            $table->dropColumn('insurance_company_id');
        });
    }
};
