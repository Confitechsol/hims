<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('organisation')) {
            return;
        }

        DB::statement('ALTER TABLE `organisation`
            MODIFY `code` VARCHAR(50) NULL,
            MODIFY `contact_no` VARCHAR(20) NULL,
            MODIFY `address` VARCHAR(300) NULL,
            MODIFY `contact_person_name` VARCHAR(200) NULL,
            MODIFY `contact_person_phone` VARCHAR(20) NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('organisation')) {
            return;
        }

        DB::statement('ALTER TABLE `organisation`
            MODIFY `code` VARCHAR(50) NOT NULL,
            MODIFY `contact_no` VARCHAR(20) NOT NULL,
            MODIFY `address` VARCHAR(300) NOT NULL,
            MODIFY `contact_person_name` VARCHAR(200) NOT NULL,
            MODIFY `contact_person_phone` VARCHAR(20) NOT NULL');
    }
};
