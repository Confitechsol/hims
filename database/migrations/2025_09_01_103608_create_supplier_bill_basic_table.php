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
        Schema::create('supplier_bill_basic', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('invoice_no', 100); // invoice number
            $table->dateTime('date'); // invoice date
            $table->unsignedBigInteger('supplier_id')->nullable()->index(); // supplier_id (FK can be added later)

            $table->string('file', 200); // file
            $table->float('total', 10, 2)->index(); // total
            $table->float('tax', 10, 2)->index(); // tax
            $table->float('discount', 10, 2)->index(); // discount
            $table->float('net_amount', 10, 2)->index(); // net_amount

            $table->text('note')->nullable(); // note

            $table->string('payment_mode', 30)->nullable()->index(); // payment_mode
            $table->string('cheque_no', 255)->nullable()->index(); // cheque_no
            $table->date('cheque_date')->nullable()->index(); // cheque_date
            $table->dateTime('payment_date')->nullable()->index(); // payment_date
            $table->unsignedBigInteger('received_by')->nullable()->index(); // received_by (employee/user)

            $table->string('attachment', 255)->nullable(); // attachment file path
            $table->string('attachment_name', 255)->nullable(); // original attachment name
            $table->text('payment_note')->nullable(); // payment note

            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_bill_basic');
    }
};
