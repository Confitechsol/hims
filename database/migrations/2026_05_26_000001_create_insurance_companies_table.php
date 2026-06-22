<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_companies', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8)->nullable();
            $table->string('branch_id', 8)->nullable();
            $table->string('name', 200)->index();
            $table->string('code', 50)->unique();
            $table->string('contact_no', 20)->nullable();
            $table->string('address', 300)->nullable();
            $table->string('contact_person_name', 200)->nullable();
            $table->string('contact_person_phone', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_companies');
    }
};
