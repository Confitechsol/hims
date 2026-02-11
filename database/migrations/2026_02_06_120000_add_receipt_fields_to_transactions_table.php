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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('receipt_no', 50)->nullable()->unique()->after('bill_id');
            $table->string('receipt_type', 50)->nullable()->after('receipt_no');
            $table->string('slip_no', 50)->nullable()->after('receipt_type');
            $table->string('booking_no', 50)->nullable()->after('slip_no');
            $table->string('final_bill_no', 50)->nullable()->after('booking_no');
            $table->decimal('tds', 12, 2)->default(0)->after('final_bill_no');
            $table->string('paid_by', 255)->nullable()->after('tds');
            $table->text('narration')->nullable()->after('paid_by');
            $table->string('remarks', 255)->nullable()->after('narration');
            $table->string('bank_name', 255)->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'receipt_no',
                'receipt_type',
                'slip_no',
                'booking_no',
                'final_bill_no',
                'tds',
                'paid_by',
                'narration',
                'remarks',
                'bank_name'
            ]);
        });
    }
};
