<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_rate_panels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('insurance_company_panel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insurance_company_id');
            $table->unsignedBigInteger('insurance_rate_panel_id');
            $table->timestamps();

            $table->unique(['insurance_company_id', 'insurance_rate_panel_id'], 'ins_co_panel_unique');
            $table->foreign('insurance_company_id')->references('id')->on('insurance_companies')->onDelete('cascade');
            $table->foreign('insurance_rate_panel_id')->references('id')->on('insurance_rate_panels')->onDelete('cascade');
        });

        Schema::create('insurance_test_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insurance_rate_panel_id');
            $table->enum('test_type', ['pathology', 'radiology']);
            $table->unsignedInteger('pathology_id')->nullable()->index();
            $table->unsignedInteger('radiology_id')->nullable()->index();
            $table->string('hospital_system_name', 300)->nullable();
            $table->string('insurer_test_name', 300);
            $table->decimal('rate', 12, 2);
            $table->enum('mapping_status', ['unmapped', 'needs_review', 'mapped'])->default('unmapped');
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('insurance_rate_panel_id')->references('id')->on('insurance_rate_panels')->onDelete('cascade');
            $table->index(['insurance_rate_panel_id', 'test_type', 'mapping_status'], 'itr_panel_type_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_test_rates');
        Schema::dropIfExists('insurance_company_panel');
        Schema::dropIfExists('insurance_rate_panels');
    }
};
