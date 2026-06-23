<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurer_room_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insurance_rate_panel_id')->nullable()->index();
            $table->unsignedBigInteger('insurance_company_id')->nullable()->index();
            $table->string('insurer_room_code', 10);
            $table->unsignedInteger('bed_group_id');
            $table->string('label', 100)->nullable();
            $table->timestamps();

            $table->foreign('insurance_rate_panel_id')
                ->references('id')->on('insurance_rate_panels')->onDelete('cascade');
            $table->foreign('insurance_company_id')
                ->references('id')->on('insurance_companies')->onDelete('cascade');
            $table->index('bed_group_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurer_room_mappings');
    }
};
