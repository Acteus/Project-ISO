<?php

namespace App\Exports;

use App\Models\SurveyResponse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyResponsesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $track;
    protected $gradeLevel;
    protected $academicYear;
    protected $semester;

    public function __construct($track = null, $gradeLevel = null, $academicYear = null, $semester = null)
    {
        $this->track = $track;
        $this->gradeLevel = $gradeLevel;
        $this->academicYear = $academicYear;
        $this->semester = $semester;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = SurveyResponse::query();

        if ($this->track) {
            $query->where('track', $this->track);
        }

        if ($this->gradeLevel) {
            $query->where('grade_level', $this->gradeLevel);
        }

        if ($this->academicYear) {
            $query->where('academic_year', $this->academicYear);
        }

        if ($this->semester) {
            $query->where('semester', $this->semester);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Anonymous ID',
            'Track',
            'Grade Level',
            'Academic Year',
            'Semester',
            // ISO 21001 Learner Needs Assessment
            'Curriculum Relevance',
            'Learning Pace Appropriateness',
            'Individual Support Availability',
            'Learning Style Accommodation',
            // ISO 21001 Learner Satisfaction Metrics
            'Teaching Quality',
            'Learning Environment',
            'Peer Interaction Satisfaction',
            'Extracurricular Satisfaction',
            // ISO 21001 Learner Success Indicators
            'Academic Progress',
            'Skill Development',
            'Critical Thinking Improvement',
            'Problem Solving Confidence',
            // ISO 21001 Learner Safety Assessment
            'Physical Safety',
            'Psychological Safety',
            'Bullying Prevention Effectiveness',
            'Emergency Preparedness',
            // ISO 21001 Learner Wellbeing Metrics
            'Mental Health Support',
            'Stress Management Support',
            'Physical Health Support',
            'Overall Wellbeing',
            // Overall Satisfaction and Feedback
            'Overall Satisfaction',
            'Consent Given',
            'Attendance Rate (%)',
            'Grade Average',
            'Participation Score',
            'Extracurricular Hours',
            'Counseling Sessions',
            'Submitted At'
        ];
    }

    public function map($response): array
    {
        return [
            $response->anonymous_id,
            $response->track,
            $response->grade_level,
            $response->academic_year,
            $response->semester,
            // ISO 21001 Learner Needs Assessment
            $response->curriculum_relevance_rating,
            $response->learning_pace_appropriateness,
            $response->individual_support_availability,
            $response->learning_style_accommodation,
            // ISO 21001 Learner Satisfaction Metrics
            $response->teaching_quality_rating,
            $response->learning_environment_rating,
            $response->peer_interaction_satisfaction,
            $response->extracurricular_satisfaction,
            // ISO 21001 Learner Success Indicators
            $response->academic_progress_rating,
            $response->skill_development_rating,
            $response->critical_thinking_improvement,
            $response->problem_solving_confidence,
            // ISO 21001 Learner Safety Assessment
            $response->physical_safety_rating,
            $response->psychological_safety_rating,
            $response->bullying_prevention_effectiveness,
            $response->emergency_preparedness_rating,
            // ISO 21001 Learner Wellbeing Metrics
            $response->mental_health_support_rating,
            $response->stress_management_support,
            $response->physical_health_support,
            $response->overall_wellbeing_rating,
            // Overall Satisfaction
            $response->overall_satisfaction,
            $response->consent_given ? 'Yes' : 'No',
            $response->attendance_rate ?? 'N/A',
            $response->grade_average ?? 'N/A',
            $response->participation_score ?? 'N/A',
            $response->extracurricular_hours ?? 'N/A',
            $response->counseling_sessions ?? 'N/A',
            $response->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
