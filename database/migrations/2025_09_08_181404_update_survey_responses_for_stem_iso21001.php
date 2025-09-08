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
            // Remove IT-specific fields
            $table->dropColumn([
                'program',
                'year_level',
                'course_content_rating',
                'facilities_rating',
                'support_services_rating'
            ]);

            // Rename student_number to student_id
            $table->renameColumn('student_number', 'student_id');

            // Add STEM student information
            $table->enum('track', ['STEM'])->default('STEM');
            $table->integer('grade_level')->after('track');
            $table->string('academic_year')->after('grade_level');
            $table->enum('semester', ['1st', '2nd'])->after('academic_year');

            // ISO 21001 Learner Needs Assessment (1-5 scale)
            $table->integer('curriculum_relevance_rating')->min(1)->max(5)->after('semester');
            $table->integer('learning_pace_appropriateness')->min(1)->max(5)->after('curriculum_relevance_rating');
            $table->integer('individual_support_availability')->min(1)->max(5)->after('learning_pace_appropriateness');
            $table->integer('learning_style_accommodation')->min(1)->max(5)->after('individual_support_availability');

            // ISO 21001 Learner Satisfaction Metrics (1-5 scale)
            $table->integer('teaching_quality_rating')->min(1)->max(5)->after('learning_style_accommodation');
            $table->integer('learning_environment_rating')->min(1)->max(5)->after('teaching_quality_rating');
            $table->integer('peer_interaction_satisfaction')->min(1)->max(5)->after('learning_environment_rating');
            $table->integer('extracurricular_satisfaction')->min(1)->max(5)->after('peer_interaction_satisfaction');

            // ISO 21001 Learner Success Indicators (1-5 scale)
            $table->integer('academic_progress_rating')->min(1)->max(5)->after('extracurricular_satisfaction');
            $table->integer('skill_development_rating')->min(1)->max(5)->after('academic_progress_rating');
            $table->integer('critical_thinking_improvement')->min(1)->max(5)->after('skill_development_rating');
            $table->integer('problem_solving_confidence')->min(1)->max(5)->after('critical_thinking_improvement');

            // ISO 21001 Learner Safety Assessment (1-5 scale)
            $table->integer('physical_safety_rating')->min(1)->max(5)->after('problem_solving_confidence');
            $table->integer('psychological_safety_rating')->min(1)->max(5)->after('physical_safety_rating');
            $table->integer('bullying_prevention_effectiveness')->min(1)->max(5)->after('psychological_safety_rating');
            $table->integer('emergency_preparedness_rating')->min(1)->max(5)->after('bullying_prevention_effectiveness');

            // ISO 21001 Learner Wellbeing Metrics (1-5 scale)
            $table->integer('mental_health_support_rating')->min(1)->max(5)->after('emergency_preparedness_rating');
            $table->integer('stress_management_support')->min(1)->max(5)->after('mental_health_support_rating');
            $table->integer('physical_health_support')->min(1)->max(5)->after('stress_management_support');
            $table->integer('overall_wellbeing_rating')->min(1)->max(5)->after('physical_health_support');

            // Overall Satisfaction
            $table->integer('overall_satisfaction')->min(1)->max(5)->after('overall_wellbeing_rating');

            // Enhanced Qualitative Feedback
            $table->text('positive_aspects')->nullable()->after('overall_satisfaction');
            $table->text('improvement_suggestions')->nullable()->after('positive_aspects');
            $table->text('additional_comments')->nullable()->after('improvement_suggestions');

            // Indirect Metrics from University Data
            $table->decimal('attendance_rate', 5, 2)->nullable()->after('additional_comments');
            $table->decimal('grade_average', 4, 2)->nullable()->after('attendance_rate');
            $table->integer('participation_score')->nullable()->after('grade_average');
            $table->integer('extracurricular_hours')->nullable()->after('participation_score');
            $table->integer('counseling_sessions')->nullable()->after('extracurricular_hours');

            // Add indexes for performance
            $table->index(['track', 'grade_level']);
            $table->index(['academic_year', 'semester']);
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
