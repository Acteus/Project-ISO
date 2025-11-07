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
        Schema::table('survey_responses', function (Blueprint $table) {
            // Add additional feedback fields (ISO 21001 compliant)
            // Note: min/max validation is handled at the application level in the controller
            if (!Schema::hasColumn('survey_responses', 'feedback_taken_seriously')) {
                $table->integer('feedback_taken_seriously')->nullable()->after('additional_comments');
            }
            if (!Schema::hasColumn('survey_responses', 'school_responsiveness')) {
                $table->integer('school_responsiveness')->nullable()->after('feedback_taken_seriously');
            }
            if (!Schema::hasColumn('survey_responses', 'visible_improvements')) {
                $table->integer('visible_improvements')->nullable()->after('school_responsiveness');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            if (Schema::hasColumn('survey_responses', 'feedback_taken_seriously')) {
                $table->dropColumn('feedback_taken_seriously');
            }
            if (Schema::hasColumn('survey_responses', 'school_responsiveness')) {
                $table->dropColumn('school_responsiveness');
            }
            if (Schema::hasColumn('survey_responses', 'visible_improvements')) {
                $table->dropColumn('visible_improvements');
            }
        });
    }
};
