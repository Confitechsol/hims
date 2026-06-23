<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_room_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->unsignedInteger('bed_group_id');
            $table->string('insurer_room_code', 10)->nullable();
            $table->string('label', 100)->nullable();
            $table->decimal('rate', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['package_id', 'bed_group_id'], 'pkg_bed_group_unique');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->index('bed_group_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_room_rates');
    }
};
