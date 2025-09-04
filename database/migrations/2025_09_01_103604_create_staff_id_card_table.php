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
        Schema::create('staff_id_card', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('title', 255);
            $table->string('hospital_name', 255);
            $table->string('hospital_address', 255);

            $table->text('background');
            $table->text('logo');
            $table->text('sign_image');

            $table->string('header_color', 100);

            $table->boolean('enable_staff_role');
            $table->boolean('enable_staff_id');
            $table->boolean('enable_staff_department');
            $table->boolean('enable_designation');
            $table->boolean('enable_name');
            $table->boolean('enable_fathers_name');
            $table->boolean('enable_mothers_name');
            $table->boolean('enable_date_of_joining');
            $table->boolean('enable_permanent_address');
            $table->boolean('enable_staff_dob');
            $table->boolean('enable_staff_phone');
            $table->boolean('enable_staff_barcode');

            $table->boolean('status');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_id_card');
    }
};
