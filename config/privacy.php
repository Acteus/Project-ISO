<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Privacy Configuration (GDPR & ISO 27001)
    |--------------------------------------------------------------------------
    |
    | This configuration file manages data privacy settings including
    | encryption, anonymization, consent management, and retention policies.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Encryption Settings
    |--------------------------------------------------------------------------
    |
    | AES-256 encryption for sensitive student data
    |
    */
    'encryption' => [
        'cipher' => 'AES-256-CBC',
        'key' => env('PRIVACY_ENCRYPTION_KEY', env('APP_KEY')),
        'encrypt_student_id' => true,
        'encrypt_comments' => true,
        'encrypted_fields' => [
            'student_id',
            'positive_aspects',
            'improvement_suggestions',
            'additional_comments',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Anonymization Settings
    |--------------------------------------------------------------------------
    |
    | SHA-256 anonymous IDs for analytics and reporting
    |
    */
    'anonymization' => [
        'algorithm' => 'sha256',
        'salt' => env('ANONYMIZATION_SALT', env('APP_KEY')),
        'use_for_analytics' => true,
        'use_for_reporting' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Consent Management
    |--------------------------------------------------------------------------
    |
    | Explicit consent validation with audit trail
    |
    */
    'consent' => [
        'require_explicit_consent' => true,
        'consent_validity_days' => 365, // Consent valid for 1 year
        'audit_consent_changes' => true,
        'consent_storage_table' => 'consent_records',
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Minimization
    |--------------------------------------------------------------------------
    |
    | Only essential ISO 21001 metrics collected
    |
    */
    'data_minimization' => [
        'enabled' => true,
        'allowed_fields' => [
            // Student Information (minimal)
            'student_id', // Encrypted
            'track',
            'grade_level',
            'academic_year',
            'semester',
            'gender',

            // ISO 21001 Learner Needs Assessment
            'curriculum_relevance_rating',
            'learning_pace_appropriateness',
            'individual_support_availability',
            'learning_style_accommodation',

            // ISO 21001 Learner Satisfaction Metrics
            'teaching_quality_rating',
            'learning_environment_rating',
            'peer_interaction_satisfaction',
            'extracurricular_satisfaction',

            // ISO 21001 Learner Success Indicators
            'academic_progress_rating',
            'skill_development_rating',
            'critical_thinking_improvement',
            'problem_solving_confidence',

            // ISO 21001 Learner Safety Assessment
            'physical_safety_rating',
            'psychological_safety_rating',
            'bullying_prevention_effectiveness',
            'emergency_preparedness_rating',

            // ISO 21001 Learner Wellbeing Metrics
            'mental_health_support_rating',
            'stress_management_support',
            'physical_health_support',
            'overall_wellbeing_rating',

            // Overall Satisfaction
            'overall_satisfaction',
            'positive_aspects', // Encrypted
            'improvement_suggestions', // Encrypted
            'additional_comments', // Encrypted

            // Indirect Metrics (ISO 21001 compliant)
            'attendance_rate',
            'grade_average',
            'participation_score',
            'extracurricular_hours',
            'counseling_sessions',

            // Privacy fields
            'consent_given',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Retention Policies
    |--------------------------------------------------------------------------
    |
    | Configurable data retention periods (in days)
    |
    */
    'retention' => [
        'enabled' => true,
        'default_retention_days' => env('DATA_RETENTION_DAYS', 2555), // ~7 years (ISO 21001 recommendation)
        'survey_responses_retention_days' => env('SURVEY_RETENTION_DAYS', 2555),
        'audit_logs_retention_days' => env('AUDIT_LOG_RETENTION_DAYS', 2555),
        'consent_records_retention_days' => env('CONSENT_RETENTION_DAYS', 2555),
        'anonymize_before_deletion' => true,
        'backup_before_deletion' => env('BACKUP_BEFORE_DELETION', false),
    ],
];


