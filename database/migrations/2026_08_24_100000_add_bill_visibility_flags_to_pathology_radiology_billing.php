<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addVisibilityColumns('pathology_billing');
        $this->addVisibilityColumns('radiology_billing');
    }

    public function down(): void
    {
        $this->dropVisibilityColumns('pathology_billing');
        $this->dropVisibilityColumns('radiology_billing');
    }

    private function addVisibilityColumns(string $tableName): void
    {
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (! Schema::hasColumn($tableName, 'show_on_approval_bill')) {
                $table->boolean('show_on_approval_bill')->default(true)->after('note');
            }
            if (! Schema::hasColumn($tableName, 'show_on_approval_preview')) {
                $table->boolean('show_on_approval_preview')->default(true)->after('show_on_approval_bill');
            }
            if (! Schema::hasColumn($tableName, 'show_on_final_preview')) {
                $table->boolean('show_on_final_preview')->default(true)->after('show_on_approval_preview');
            }
            if (! Schema::hasColumn($tableName, 'show_on_final_bill')) {
                $table->boolean('show_on_final_bill')->default(true)->after('show_on_final_preview');
            }
        });
    }

    private function dropVisibilityColumns(string $tableName): void
    {
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            foreach ([
                'show_on_approval_bill',
                'show_on_approval_preview',
                'show_on_final_preview',
                'show_on_final_bill',
            ] as $col) {
                if (Schema::hasColumn($tableName, $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
