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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('employee_id', 200)->unique()->nullable();
            $table->unsignedBigInteger('lang_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('staff_designation_id')->nullable();

            $table->string('specialist', 200);
            $table->string('qualification', 200);
            $table->string('work_exp', 200);
            $table->string('specialization', 200);

            $table->string('name', 200);
            $table->string('surname', 200);
            $table->string('father_name', 200);
            $table->string('mother_name', 200);
            $table->string('contact_no', 200);
            $table->string('emergency_contact_no', 200);
            $table->string('email', 200);

            $table->date('dob')->nullable();
            $table->string('marital_status', 100);

            $table->date('date_of_joining')->nullable();
            $table->date('date_of_leaving')->nullable();

            $table->string('local_address', 300);
            $table->string('permanent_address', 200);
            $table->string('note', 200);
            $table->string('image', 200);

            $table->string('password', 250);
            $table->string('gender', 50);
            $table->string('blood_group', 100);

            $table->string('account_title', 200);
            $table->string('bank_account_no', 200);
            $table->string('bank_name', 200);
            $table->string('ifsc_code', 200);
            $table->string('bank_branch', 100);

            $table->string('payscale', 200);
            $table->string('basic_salary', 200);
            $table->string('epf_no', 200);

            $table->string('contract_type', 100);
            $table->string('shift', 100);
            $table->string('location', 100);

            $table->string('facebook', 200);
            $table->string('twitter', 200);
            $table->string('linkedin', 200);
            $table->string('instagram', 200);

            $table->string('resume', 200);
            $table->string('joining_letter', 200);
            $table->string('resignation_letter', 200);
            $table->string('other_document_name', 200);
            $table->string('other_document_file', 200);

            $table->unsignedBigInteger('user_id');

            $table->integer('is_active');
            $table->string('verification_code', 100);

            $table->string('zoom_api_key', 100);
            $table->string('zoom_api_secret', 100);

            $table->string('pan_number', 30);
            $table->string('identification_number', 30);
            $table->string('local_identification_number', 30);

            $table->timestamp('created_at')->useCurrent();

            // (Optional) Foreign keys if you want to enforce relations
            // $table->foreign('department_id')->references('id')->on('departments');
            // $table->foreign('staff_designation_id')->references('id')->on('designations');
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
