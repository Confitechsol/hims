<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_company_organisation', function (Blueprint $table) {
            $table->id();
            // organisation.id is INT(11) in legacy schema, not BIGINT
            $table->integer('organisation_id');
            $table->unsignedBigInteger('insurance_company_id');
            $table->timestamps();

            $table->unique(['organisation_id', 'insurance_company_id'], 'org_insurance_unique');
            $table->foreign('organisation_id')->references('id')->on('organisation')->onDelete('cascade');
            $table->foreign('insurance_company_id')->references('id')->on('insurance_companies')->onDelete('cascade');
        });

        if (Schema::hasColumn('organisation', 'insurance_company_id')) {
            $rows = DB::table('organisation')
                ->whereNotNull('insurance_company_id')
                ->select('id', 'insurance_company_id')
                ->get();

            foreach ($rows as $row) {
                DB::table('insurance_company_organisation')->insertOrIgnore([
                    'organisation_id' => $row->id,
                    'insurance_company_id' => $row->insurance_company_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_company_organisation');
    }
};
