<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipd_charges', function (Blueprint $table) {
            if (! Schema::hasColumn('ipd_charges', 'show_on_approval_bill')) {
                $table->boolean('show_on_approval_bill')->default(true)->after('date');
            }
            if (! Schema::hasColumn('ipd_charges', 'show_on_approval_preview')) {
                $table->boolean('show_on_approval_preview')->default(true)->after('show_on_approval_bill');
            }
            if (! Schema::hasColumn('ipd_charges', 'show_on_final_preview')) {
                $table->boolean('show_on_final_preview')->default(true)->after('show_on_approval_preview');
            }
            if (! Schema::hasColumn('ipd_charges', 'show_on_final_bill')) {
                $table->boolean('show_on_final_bill')->default(true)->after('show_on_final_preview');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ipd_charges', function (Blueprint $table) {
            $cols = [
                'show_on_approval_bill',
                'show_on_approval_preview',
                'show_on_final_preview',
                'show_on_final_bill',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('ipd_charges', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
