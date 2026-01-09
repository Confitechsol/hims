<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organisations_charges', function (Blueprint $table) {
            // Add pathology_id and charge_type columns for pathology TPA charges
            // charge_id will remain for other modules (like general charges)
            $table->unsignedBigInteger('pathology_id')->nullable()->index()->after('charge_id');
            $table->enum('charge_type', ['IPD', 'OPD'])->nullable()->after('pathology_id');
            
            // Add foreign key for pathology_id
            $table->foreign('pathology_id')->references('id')->on('pathology')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organisations_charges', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['pathology_id']);
            
            // Drop columns
            $table->dropColumn(['pathology_id', 'charge_type']);
        });
    }
};
