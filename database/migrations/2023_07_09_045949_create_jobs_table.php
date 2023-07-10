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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title');
            $table->text('description');
            $table->text('category');
            $table->string('apply_url');
            $table->bigInteger('apply_count')->default(0);
            $table->string('position');
            $table->text('salary')->nullable();
            $table->text('locations');
            $table->text('skills')->nullable();
            $table->text('unique_id');
            $table->string('seo_image')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('source')->nullable();
            $table->tinyInteger('status')->comment('1 => pending, 2 => approved, 3 => disabled');
            $table->timestamps();
        });

        Schema::table('jobs', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
