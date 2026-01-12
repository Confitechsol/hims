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
        Schema::table('pathology', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['charge_category_id']);
            $table->dropForeign(['charge_id']);
            
            // Drop indexes
            $table->dropIndex(['charge_category_id']);
            $table->dropIndex(['charge_id']);
            
            // Drop old columns
            $table->dropColumn(['charge_category_id', 'charge_id']);
            
            // Add new columns for IPD and OPD charges
            $table->decimal('standard_charge_ipd', 10, 2)->nullable()->after('method');
            $table->decimal('standard_charge_opd', 10, 2)->nullable()->after('standard_charge_ipd');
            
            // Keep standard_charge and amount for backward compatibility, but they'll be deprecated
            // We'll use standard_charge_ipd and standard_charge_opd going forward
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['standard_charge_ipd', 'standard_charge_opd']);
            
            // Re-add old columns
            $table->unsignedBigInteger('charge_category_id')->nullable()->index()->after('method');
            $table->unsignedBigInteger('charge_id')->nullable()->index()->after('charge_category_id');
            
            // Re-add foreign keys
            $table->foreign('charge_category_id')->references('id')->on('charge_categories')->onDelete('set null');
            $table->foreign('charge_id')->references('id')->on('charges')->onDelete('set null');
        });
    }
};
