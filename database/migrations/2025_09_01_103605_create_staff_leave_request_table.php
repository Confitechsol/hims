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
        Schema::create('staff_leave_request', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('staff_id')->nullable();       // staff_id
            $table->unsignedBigInteger('leave_type_id')->nullable();  // leave_type_id

            $table->date('leave_from'); // leave_from
            $table->date('leave_to');   // leave_to
            $table->integer('leave_days'); // leave_days

            $table->string('employee_remark', 200); // employee_remark
            $table->string('admin_remark', 200);    // admin_remark
            $table->string('status', 100);          // status

            $table->date('approved_date')->nullable(); // approved_date
            $table->unsignedBigInteger('applied_by')->nullable(); // applied_by
            $table->unsignedBigInteger('status_updated_by')->nullable(); // status_updated_by

            $table->text('document_file'); // document_file
            $table->date('date');          // date of application

            $table->timestamp('created_at')->useCurrent(); // created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_leave_request');
    }
};
