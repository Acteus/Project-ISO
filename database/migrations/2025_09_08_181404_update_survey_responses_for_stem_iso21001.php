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
            // Drop the legacy index first to avoid SQLite errors when dropping columns
            if (Schema::hasIndex('survey_responses', 'survey_responses_program_year_level_index')) {
                $table->dropIndex('survey_responses_program_year_level_index');
            }

            // Remove IT-specific fields that still exist
            if (Schema::hasColumn('survey_responses', 'program')) {
                $table->dropColumn('program');
            }
            if (Schema::hasColumn('survey_responses', 'year_level')) {
                $table->dropColumn('year_level');
            }
            if (Schema::hasColumn('survey_responses', 'course_content_rating')) {
                $table->dropColumn('course_content_rating');
            }
            if (Schema::hasColumn('survey_responses', 'facilities_rating')) {
                $table->dropColumn('facilities_rating');
            }
            if (Schema::hasColumn('survey_responses', 'support_services_rating')) {
                $table->dropColumn('support_services_rating');
            }

            // Rename student_number to student_id if it exists
            if (Schema::hasColumn('survey_responses', 'student_number')) {
                $table->renameColumn('student_number', 'student_id');
            }

            // Add STEM student information
            if (!Schema::hasColumn('survey_responses', 'track')) {
                $table->enum('track', ['STEM'])->default('STEM');
            }
            if (!Schema::hasColumn('survey_responses', 'grade_level')) {
                $table->integer('grade_level')->after('track');
            }
            if (!Schema::hasColumn('survey_responses', 'academic_year')) {
                $table->string('academic_year')->after('grade_level');
            }
            if (!Schema::hasColumn('survey_responses', 'semester')) {
                $table->enum('semester', ['1st', '2nd'])->after('academic_year');
            }

            // ISO 21001 Learner Needs Assessment (1-5 scale)
            if (!Schema::hasColumn('survey_responses', 'curriculum_relevance_rating')) {
                $table->integer('curriculum_relevance_rating')->min(1)->max(5)->after('semester');
            }
            if (!Schema::hasColumn('survey_responses', 'learning_pace_appropriateness')) {
                $table->integer('learning_pace_appropriateness')->min(1)->max(5)->after('curriculum_relevance_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'individual_support_availability')) {
                $table->integer('individual_support_availability')->min(1)->max(5)->after('learning_pace_appropriateness');
            }
            if (!Schema::hasColumn('survey_responses', 'learning_style_accommodation')) {
                $table->integer('learning_style_accommodation')->min(1)->max(5)->after('individual_support_availability');
            }

            // ISO 21001 Learner Satisfaction Metrics (1-5 scale)
            if (!Schema::hasColumn('survey_responses', 'teaching_quality_rating')) {
                $table->integer('teaching_quality_rating')->min(1)->max(5)->after('learning_style_accommodation');
            }
            if (!Schema::hasColumn('survey_responses', 'learning_environment_rating')) {
                $table->integer('learning_environment_rating')->min(1)->max(5)->after('teaching_quality_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'peer_interaction_satisfaction')) {
                $table->integer('peer_interaction_satisfaction')->min(1)->max(5)->after('learning_environment_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'extracurricular_satisfaction')) {
                $table->integer('extracurricular_satisfaction')->min(1)->max(5)->after('peer_interaction_satisfaction');
            }

            // ISO 21001 Learner Success Indicators (1-5 scale)
            if (!Schema::hasColumn('survey_responses', 'academic_progress_rating')) {
                $table->integer('academic_progress_rating')->min(1)->max(5)->after('extracurricular_satisfaction');
            }
            if (!Schema::hasColumn('survey_responses', 'skill_development_rating')) {
                $table->integer('skill_development_rating')->min(1)->max(5)->after('academic_progress_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'critical_thinking_improvement')) {
                $table->integer('critical_thinking_improvement')->min(1)->max(5)->after('skill_development_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'problem_solving_confidence')) {
                $table->integer('problem_solving_confidence')->min(1)->max(5)->after('critical_thinking_improvement');
            }

            // ISO 21001 Learner Safety Assessment (1-5 scale)
            if (!Schema::hasColumn('survey_responses', 'physical_safety_rating')) {
                $table->integer('physical_safety_rating')->min(1)->max(5)->after('problem_solving_confidence');
            }
            if (!Schema::hasColumn('survey_responses', 'psychological_safety_rating')) {
                $table->integer('psychological_safety_rating')->min(1)->max(5)->after('physical_safety_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'bullying_prevention_effectiveness')) {
                $table->integer('bullying_prevention_effectiveness')->min(1)->max(5)->after('psychological_safety_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'emergency_preparedness_rating')) {
                $table->integer('emergency_preparedness_rating')->min(1)->max(5)->after('bullying_prevention_effectiveness');
            }

            // ISO 21001 Learner Wellbeing Metrics (1-5 scale)
            if (!Schema::hasColumn('survey_responses', 'mental_health_support_rating')) {
                $table->integer('mental_health_support_rating')->min(1)->max(5)->after('emergency_preparedness_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'stress_management_support')) {
                $table->integer('stress_management_support')->min(1)->max(5)->after('mental_health_support_rating');
            }
            if (!Schema::hasColumn('survey_responses', 'physical_health_support')) {
                $table->integer('physical_health_support')->min(1)->max(5)->after('stress_management_support');
            }
            if (!Schema::hasColumn('survey_responses', 'overall_wellbeing_rating')) {
                $table->integer('overall_wellbeing_rating')->min(1)->max(5)->after('physical_health_support');
            }

            // Overall Satisfaction
            if (!Schema::hasColumn('survey_responses', 'overall_satisfaction')) {
                $table->integer('overall_satisfaction')->min(1)->max(5)->after('overall_wellbeing_rating');
            }

            // Enhanced Qualitative Feedback
            if (!Schema::hasColumn('survey_responses', 'positive_aspects')) {
                $table->text('positive_aspects')->nullable()->after('overall_satisfaction');
            }
            if (!Schema::hasColumn('survey_responses', 'improvement_suggestions')) {
                $table->text('improvement_suggestions')->nullable()->after('positive_aspects');
            }
            if (!Schema::hasColumn('survey_responses', 'additional_comments')) {
                $table->text('additional_comments')->nullable()->after('improvement_suggestions');
            }

            // Indirect Metrics from University Data
            if (!Schema::hasColumn('survey_responses', 'attendance_rate')) {
                $table->decimal('attendance_rate', 5, 2)->nullable()->after('additional_comments');
            }
            if (!Schema::hasColumn('survey_responses', 'grade_average')) {
                $table->decimal('grade_average', 4, 2)->nullable()->after('attendance_rate');
            }
            if (!Schema::hasColumn('survey_responses', 'participation_score')) {
                $table->integer('participation_score')->nullable()->after('grade_average');
            }
            if (!Schema::hasColumn('survey_responses', 'extracurricular_hours')) {
                $table->integer('extracurricular_hours')->nullable()->after('participation_score');
            }
            if (!Schema::hasColumn('survey_responses', 'counseling_sessions')) {
                $table->integer('counseling_sessions')->nullable()->after('extracurricular_hours');
            }

            // Add indexes for performance
            if (!Schema::hasIndex('survey_responses', ['track', 'grade_level'])) {
                $table->index(['track', 'grade_level']);
            }
            if (!Schema::hasIndex('survey_responses', ['academic_year', 'semester'])) {
                $table->index(['academic_year', 'semester']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            // Reverse the changes - add back IT-specific fields
            $table->enum('program', ['BSIT', 'BSIT-BA'])->after('student_id');
            $table->integer('year_level')->after('program');
            $table->integer('course_content_rating')->min(1)->max(5)->after('year_level');
            $table->integer('facilities_rating')->min(1)->max(5)->after('course_content_rating');
            $table->integer('support_services_rating')->min(1)->max(5)->after('facilities_rating');

            // Remove new STEM/ISO 21001 fields
            $table->dropColumn([
                'track',
                'grade_level',
                'academic_year',
                'semester',
                'curriculum_relevance_rating',
                'learning_pace_appropriateness',
                'individual_support_availability',
                'learning_style_accommodation',
                'teaching_quality_rating',
                'learning_environment_rating',
                'peer_interaction_satisfaction',
                'extracurricular_satisfaction',
                'academic_progress_rating',
                'skill_development_rating',
                'critical_thinking_improvement',
                'problem_solving_confidence',
                'physical_safety_rating',
                'psychological_safety_rating',
                'bullying_prevention_effectiveness',
                'emergency_preparedness_rating',
                'mental_health_support_rating',
                'stress_management_support',
                'physical_health_support',
                'overall_wellbeing_rating',
                'positive_aspects',
                'improvement_suggestions',
                'additional_comments',
                'attendance_rate',
                'grade_average',
                'participation_score',
                'extracurricular_hours',
                'counseling_sessions'
            ]);

            // Rename back to student_number
            $table->renameColumn('student_id', 'student_number');

            // Drop indexes
            $table->dropIndex(['track', 'grade_level']);
            $table->dropIndex(['academic_year', 'semester']);
        });
    }
};
