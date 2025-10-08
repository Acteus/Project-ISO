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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->string('student_number')->unique();
            $table->enum('program', ['BSIT', 'BSIT-BA']);
            $table->integer('year_level');
            $table->integer('course_content_rating')->min(1)->max(5);
            $table->integer('facilities_rating')->min(1)->max(5);
            $table->integer('support_services_rating')->min(1)->max(5);
            $table->integer('overall_satisfaction')->min(1)->max(5);
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->index(['program', 'year_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
