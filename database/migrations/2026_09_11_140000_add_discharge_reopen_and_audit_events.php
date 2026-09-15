<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            if (! Schema::hasColumn('ipd_details', 'is_reopened')) {
                $table->boolean('is_reopened')->default(false)->after('physical_release_at');
            }
            if (! Schema::hasColumn('ipd_details', 'reopened_at')) {
                $table->timestamp('reopened_at')->nullable()->after('is_reopened');
            }
            if (! Schema::hasColumn('ipd_details', 'reopened_by')) {
                $table->unsignedBigInteger('reopened_by')->nullable()->after('reopened_at');
            }
            if (! Schema::hasColumn('ipd_details', 'reopen_reason')) {
                $table->text('reopen_reason')->nullable()->after('reopened_by');
            }
            if (! Schema::hasColumn('ipd_details', 'reopen_closed_at')) {
                $table->timestamp('reopen_closed_at')->nullable()->after('reopen_reason');
            }
        });

        if (! Schema::hasTable('audit_events')) {
            Schema::create('audit_events', function (Blueprint $table) {
                $table->id();
                $table->string('hospital_id', 16)->nullable()->index();
                $table->string('branch_id', 16)->nullable()->index();
                $table->dateTime('occurred_at')->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('user_role_name', 100)->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent', 500)->nullable();

                $table->string('module', 32)->index();
                $table->string('entity_type', 64)->index();
                $table->unsignedBigInteger('entity_id')->nullable()->index();
                $table->string('parent_type', 64)->nullable();
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->unsignedBigInteger('patient_id')->nullable()->index();
                $table->string('case_no', 64)->nullable()->index();

                $table->string('action', 48)->index();
                $table->text('reason')->nullable();
                $table->string('request_route', 255)->nullable();
                $table->string('request_method', 10)->nullable();

                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->json('meta')->nullable();

                $table->timestamps();

                $table->index(['hospital_id', 'occurred_at']);
                $table->index(['module', 'action', 'occurred_at']);
                $table->index(['parent_id', 'module', 'occurred_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');

        Schema::table('ipd_details', function (Blueprint $table) {
            foreach (['reopen_closed_at', 'reopen_reason', 'reopened_by', 'reopened_at', 'is_reopened'] as $col) {
                if (Schema::hasColumn('ipd_details', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
