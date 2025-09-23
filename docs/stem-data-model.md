# STEM Senior High School Data Model - ISO 21001 Aligned

## Overview

This document defines the new data model for Senior High School STEM students, aligned with ISO 21001:2018 Educational Organizations Management Systems standard, focusing on learner-centric approaches.

## ISO 21001 Core Principles Integration

### Learner-Centric Focus Areas:
1. **Learner Needs & Expectations**: Understanding diverse learning requirements
2. **Learner Satisfaction**: Measuring contentment with educational experience
3. **Learner Success**: Academic achievement and skill development
4. **Learner Safety**: Physical and psychological well-being
5. **Learner Wellbeing**: Holistic health and personal development

## New Database Schema

### Student Information
```sql
-- Updated from program enum to track type
track: ENUM('STEM') -- Can be expanded later for other tracks
grade_level: INTEGER CHECK (grade_level IN (11, 12)) -- Senior High School levels
student_id: STRING -- Encrypted student identifier
academic_year: STRING -- e.g., "2024-2025"
semester: ENUM('1st', '2nd')
```

### ISO 21001 Aligned Survey Fields

#### 1. Learner Needs Assessment (1-5 scale)
- `curriculum_relevance_rating`: How well curriculum meets career goals
- `learning_pace_appropriateness`: Suitability of teaching pace
- `individual_support_availability`: Access to personalized help
- `learning_style_accommodation`: Adaptation to different learning preferences

#### 2. Learner Satisfaction Metrics (1-5 scale)
- `teaching_quality_rating`: Quality of instruction received
- `learning_environment_rating`: Classroom and lab environment quality
- `peer_interaction_satisfaction`: Quality of student collaboration
- `extracurricular_satisfaction`: STEM clubs, competitions, activities

#### 3. Learner Success Indicators (1-5 scale)
- `academic_progress_rating`: Personal assessment of learning progress
- `skill_development_rating`: Development of STEM skills
- `critical_thinking_improvement`: Enhancement of analytical abilities
- `problem_solving_confidence`: Confidence in tackling STEM problems

#### 4. Learner Safety Assessment (1-5 scale)
- `physical_safety_rating`: Safety of learning facilities and equipment
- `psychological_safety_rating`: Feeling of safety in expressing ideas
- `bullying_prevention_effectiveness`: Effectiveness of anti-bullying measures
- `emergency_preparedness_rating`: Preparedness for emergencies

#### 5. Learner Wellbeing Metrics (1-5 scale)
- `mental_health_support_rating`: Access to mental health resources
- `stress_management_support`: Support for managing academic stress
- `physical_health_support`: Access to health services
- `overall_wellbeing_rating`: General sense of wellbeing at school

#### 6. Indirect Metrics Integration (University data sources)
- `attendance_rate`: Percentage of classes attended
- `grade_average`: Current GPA or average grade
- `participation_score`: Engagement in class activities
- `extracurricular_hours`: Hours spent in STEM-related activities
- `counseling_sessions`: Number of counseling sessions attended

### Complete Survey Response Table Schema
```php
// Migration structure for survey_responses table
$table->string('student_id')->unique(); // Encrypted
$table->enum('track', ['STEM']);
$table->integer('grade_level'); // 11 or 12
$table->string('academic_year');
$table->enum('semester', ['1st', '2nd']);

// ISO 21001 Learner Needs (1-5 scale)
$table->integer('curriculum_relevance_rating')->min(1)->max(5);
$table->integer('learning_pace_appropriateness')->min(1)->max(5);
$table->integer('individual_support_availability')->min(1)->max(5);
$table->integer('learning_style_accommodation')->min(1)->max(5);

// ISO 21001 Learner Satisfaction (1-5 scale)
$table->integer('teaching_quality_rating')->min(1)->max(5);
$table->integer('learning_environment_rating')->min(1)->max(5);
$table->integer('peer_interaction_satisfaction')->min(1)->max(5);
$table->integer('extracurricular_satisfaction')->min(1)->max(5);

// ISO 21001 Learner Success (1-5 scale)
$table->integer('academic_progress_rating')->min(1)->max(5);
$table->integer('skill_development_rating')->min(1)->max(5);
$table->integer('critical_thinking_improvement')->min(1)->max(5);
$table->integer('problem_solving_confidence')->min(1)->max(5);

// ISO 21001 Learner Safety (1-5 scale)
$table->integer('physical_safety_rating')->min(1)->max(5);
$table->integer('psychological_safety_rating')->min(1)->max(5);
$table->integer('bullying_prevention_effectiveness')->min(1)->max(5);
$table->integer('emergency_preparedness_rating')->min(1)->max(5);

// ISO 21001 Learner Wellbeing (1-5 scale)
$table->integer('mental_health_support_rating')->min(1)->max(5);
$table->integer('stress_management_support')->min(1)->max(5);
$table->integer('physical_health_support')->min(1)->max(5);
$table->integer('overall_wellbeing_rating')->min(1)->max(5);

// Overall Satisfaction
$table->integer('overall_satisfaction')->min(1)->max(5);

// Qualitative Feedback
$table->text('positive_aspects')->nullable(); // What students like
$table->text('improvement_suggestions')->nullable(); // Areas for improvement
$table->text('additional_comments')->nullable(); // Open feedback

// Privacy and Consent
$table->boolean('consent_given')->default(false);
$table->string('ip_address')->nullable();

// Indirect Metrics (from university data)
$table->decimal('attendance_rate', 5, 2)->nullable(); // Percentage
$table->decimal('grade_average', 4, 2)->nullable(); // GPA
$table->integer('participation_score')->nullable(); // 0-100
$table->integer('extracurricular_hours')->nullable(); // Monthly hours
$table->integer('counseling_sessions')->nullable(); // Number of sessions

// Timestamps
$table->timestamps();

// Indexes for performance
$table->index(['track', 'grade_level']);
$table->index(['academic_year', 'semester']);
$table->index('created_at');
```

### Model Fillable Attributes
```php
protected $fillable = [
    'student_id', 'track', 'grade_level', 'academic_year', 'semester',
    // Learner Needs
    'curriculum_relevance_rating', 'learning_pace_appropriateness',
    'individual_support_availability', 'learning_style_accommodation',
    // Learner Satisfaction
    'teaching_quality_rating', 'learning_environment_rating',
    'peer_interaction_satisfaction', 'extracurricular_satisfaction',
    // Learner Success
    'academic_progress_rating', 'skill_development_rating',
    'critical_thinking_improvement', 'problem_solving_confidence',
    // Learner Safety
    'physical_safety_rating', 'psychological_safety_rating',
    'bullying_prevention_effectiveness', 'emergency_preparedness_rating',
    // Learner Wellbeing
    'mental_health_support_rating', 'stress_management_support',
    'physical_health_support', 'overall_wellbeing_rating',
    // Overall
    'overall_satisfaction', 'positive_aspects', 'improvement_suggestions',
    'additional_comments', 'consent_given', 'ip_address',
    // Indirect Metrics
    'attendance_rate', 'grade_average', 'participation_score',
    'extracurricular_hours', 'counseling_sessions'
];
```

### Data Validation and Cross-Referencing Strategy

#### Direct vs Indirect Metrics Validation:
1. **Satisfaction vs Performance**: Cross-reference `overall_satisfaction` with `grade_average`
2. **Safety Perception vs Attendance**: Compare `psychological_safety_rating` with `attendance_rate`
3. **Wellbeing vs Counseling**: Correlate `mental_health_support_rating` with `counseling_sessions`
4. **Success vs Participation**: Compare `skill_development_rating` with `participation_score`

#### Analytics Categories:
1. **Learner Needs Index**: Average of needs assessment ratings
2. **Satisfaction Score**: Composite of satisfaction metrics
3. **Safety Index**: Average of safety-related ratings
4. **Wellbeing Composite**: Overall wellbeing assessment
5. **Performance Correlation**: Analysis of direct vs indirect metrics

## Migration Strategy

1. **Create new migration** to add ISO 21001 fields
2. **Update existing migration** to change program to track
3. **Create data transformation script** for existing IT data (if needed)
4. **Update model relationships** and validation rules
5. **Test data integrity** after migration

## Implementation Notes

- All rating fields use 1-5 Likert scale for consistency
- Text fields are encrypted for privacy
- Indirect metrics are nullable to accommodate phased integration
- Indexes optimized for common query patterns
- Data model supports future expansion to other tracks
