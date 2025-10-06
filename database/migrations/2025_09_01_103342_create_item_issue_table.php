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
        Schema::create('item_issue', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->integer('issue_type')->nullable();
            $table->unsignedBigInteger('issue_to')->nullable()->index();
            $table->string('issue_by', 100)->nullable();
            $table->date('issue_date')->nullable()->index();
            $table->date('return_date')->nullable()->index();

            $table->unsignedBigInteger('item_category_id')->nullable()->index();
            $table->unsignedBigInteger('item_id')->nullable()->index();

            $table->integer('quantity')->index();
            $table->text('note')->nullable();

            $table->integer('is_returned')->default(1)->index();
            $table->string('is_active', 10)->nullable()->default('no');
            $table->timestamps();
            $table->foreign('item_category_id')->references('id')->on('item_category')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_issue');
    }
};