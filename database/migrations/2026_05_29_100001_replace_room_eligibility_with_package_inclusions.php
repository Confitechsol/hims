<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'package_inclusions')) {
                $table->string('package_inclusions', 50)->nullable()->after('speciality');
            }
            if (!Schema::hasColumn('packages', 'package_exclusions')) {
                $table->string('package_exclusions', 50)->nullable()->after('package_inclusions');
            }
        });

        if (Schema::hasColumn('packages', 'room_eligibility')) {
            DB::table('packages')
                ->whereNotNull('room_eligibility')
                ->where('room_eligibility', '!=', '')
                ->update([
                    'package_inclusions' => DB::raw('room_eligibility'),
                ]);

            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('room_eligibility');
            });
        }
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'room_eligibility')) {
                $table->string('room_eligibility', 20)->nullable()->after('speciality');
            }
        });

        if (Schema::hasColumn('packages', 'package_inclusions')) {
            DB::table('packages')
                ->whereNotNull('package_inclusions')
                ->update(['room_eligibility' => DB::raw('package_inclusions')]);
        }

        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'package_exclusions')) {
                $table->dropColumn('package_exclusions');
            }
            if (Schema::hasColumn('packages', 'package_inclusions')) {
                $table->dropColumn('package_inclusions');
            }
        });
    }
};
