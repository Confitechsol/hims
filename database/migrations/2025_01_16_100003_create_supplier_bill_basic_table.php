<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::create('supplier_bill_basic', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 100)->nullable();
            $table->dateTime('date');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('file', 200)->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->string('payment_mode', 30)->nullable();
            $table->string('cheque_no', 255)->nullable();
            $table->date('cheque_date')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->string('attachment', 255)->nullable();
            $table->string('attachment_name', 255)->nullable();
            $table->text('payment_note')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('supplier_id')
                ->references('id')->on('medicine_suppliers')
                ->onDelete('cascade');
            
            $table->foreign('received_by')
                ->references('id')->on('users')
                ->onDelete('set null');

            // Indexes for performance
            $table->index('total');
            $table->index('payment_mode');
            $table->index('payment_date');
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_bill_basic');
    }
};

