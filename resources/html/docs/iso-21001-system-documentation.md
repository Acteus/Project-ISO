# ISO 21001 STEM Survey System Documentation

## Overview

This documentation describes the Project-ISO system, a comprehensive Laravel-based application designed for STEM educational institutions to achieve and maintain compliance with [ISO 21001:2018 - Educational organizations — Management systems for educational organizations — Requirements with guidance for use](https://www.iso.org/standard/66266.html). The system provides:

- **Learner-centered survey collection** with comprehensive ISO 21001 metrics
- **Data privacy and consent management** aligned with GDPR and ISO 27001 principles
- **Advanced analytics and AI-driven insights** for continuous improvement
- **Comprehensive validation and quality assurance** processes
- **Export and reporting capabilities** for accreditation and internal review
- **Audit trails and traceability** for compliance verification

## System Architecture

### Core Components

1. **SurveyResponse Model** (`app/Models/SurveyResponse.php`)
   - Collects comprehensive ISO 21001 metrics across 5 key domains:
     - **Learner Needs Assessment** (Curriculum relevance, learning pace, individual support, learning style accommodation)
     - **Learner Satisfaction Metrics** (Teaching quality, learning environment, peer interaction, extracurricular activities)
     - **Learner Success Indicators** (Academic progress, skill development, critical thinking, problem solving)
     - **Learner Safety Assessment** (Physical safety, psychological safety, bullying prevention, emergency preparedness)
     - **Learner Wellbeing Metrics** (Mental health support, stress management, physical health, overall wellbeing)
   - **Privacy Features**:
     - Automatic encryption of sensitive data (student ID, comments) using Laravel's Crypt facade
     - Hidden attributes for sensitive fields in JSON responses
     - Anonymous ID generation for analytics (SHA-256 hash of student ID + timestamp)
     - IP address tracking for audit purposes
   - **Data Integrity**:
     - Boolean consent field with validation enforcement
     - Type casting for numeric ratings (1-5 scale) and decimal metrics
     - Fillable array protection against mass assignment vulnerabilities

2. **SurveyController** (`app/Http/Controllers/SurveyController.php`)
   - **POST /api/survey/submit** - Submit new survey response with comprehensive validation
     - Validates all ISO 21001 metrics (1-5 scale)
     - Ensures consent is explicitly given
     - Encrypts sensitive data before storage
     - Creates audit log entry for traceability (ISO 21001:8.2.4)
   - **GET /api/survey/analytics** - Retrieve ISO 21001 composite indices and correlations
     - Calculates 5 core ISO 21001 indices (Learner Needs, Satisfaction, Success, Safety, Wellbeing)
     - Provides correlation analysis between satisfaction and performance metrics
     - Logs access for audit trail
     - Returns distribution analysis by demographics
   - **GET /api/survey/responses** - Paginated response listing with privacy controls
     - Uses anonymous IDs instead of student identifiers
     - Hides sensitive comment fields by default
     - Supports filtering by track, grade level, academic year, semester
   - **DELETE /api/survey/{id}** - Delete response with full audit logging

3. **ValidationService** (`app/Services/ValidationService.php`)
   - **validateDirectVsIndirect()** - Cross-references self-reported satisfaction with objective performance metrics
     - Identifies high satisfaction/low performance discrepancies
     - Detects safety concerns vs attendance patterns
     - Analyzes wellbeing ratings vs counseling usage
     - Provides validation scoring (0-100) with severity levels
   - **validateAccessibilityCompliance()** - ISO 21001:7.1.3 learner needs assessment
     - Checks learning style accommodation ratings
     - Validates individual support availability
     - Assesses learning pace appropriateness for diverse abilities
     - Ensures curriculum relevance for diverse backgrounds
   - **validateDataQuality()** - ISO 21001:8.3.2 data quality assurance
     - Validates consent documentation completeness
     - Detects missing core ISO 21001 metrics
     - Identifies statistical outliers using z-score analysis
     - Checks temporal consistency to detect automated submissions
     - Logs validation results for continuous monitoring
   - **generateComprehensiveComplianceReport()** - Full ISO 21001 compliance assessment
     - Combines all validation types with weighted scoring
     - Provides priority areas for improvement
     - Generates actionable recommendations with severity levels
     - Determines overall compliance status (Fully/Mostly/Partially/Non-Compliant)

4. **AIController & AIService** (`app/Http/Controllers/AIController.php`, `app/Services/AIService.php`)
   - **POST /api/ai/predict-compliance** - AI-driven compliance prediction
     - Uses weighted ISO 21001 indices to predict compliance levels
     - Provides confidence scores and risk assessments
     - Generates prioritized recommendations based on weak areas
   - **GET /api/ai/cluster-responses** - K-means clustering of learner profiles
     - Groups similar learner experiences for targeted interventions
     - Analyzes demographic patterns within clusters
     - Provides cluster-specific recommendations
   - **GET /api/ai/analyze-sentiment** - NLP sentiment analysis of open feedback
     - Categorizes sentiment by ISO 21001 themes (teaching, safety, wellbeing)
     - Identifies positive/negative/neutral sentiment distribution
     - Provides actionable insights from qualitative data
   - **GET /api/ai/extract-keywords** - Thematic keyword extraction
     - Identifies recurring themes in learner feedback
     - Categorizes keywords by ISO 21001 domains
     - Supports minimum frequency thresholds for significance

5. **ExportController** (`app/Http/Controllers/ExportController.php`)
   - **GET /api/export/excel** - Excel export of survey responses
     - Uses anonymous IDs for privacy compliance
     - Includes all ISO 21001 metrics and indirect performance data
     - Supports filtering by demographics
     - Creates comprehensive audit log entry
   - **GET /api/export/csv** - CSV export format for data analysis
   - **GET /api/export/pdf** - PDF survey report with analytics summary
     - Includes executive summary with ISO 21001 indices
     - Provides detailed response tables with key metrics
     - Features professional formatting for accreditation reports
   - **GET /api/export/analytics-report** - Comprehensive analytics PDF
     - Detailed correlation analysis and distribution charts
     - ISO 21001 compliance scoring and recommendations
     - Suitable for quality management reviews and accreditation

6. **VisualizationController & VisualizationService** (`app/Http/Controllers/VisualizationController.php`, `app/Services/VisualizationService.php`)
   - **GET /api/visualization/bar-chart** - ISO 21001 indices comparison
   - **GET /api/visualization/pie-chart** - Satisfaction distribution analysis
   - **GET /api/visualization/radar-chart** - Multi-dimensional performance profile
   - **GET /api/visualization/word-cloud** - Thematic analysis of open feedback
   - **GET /api/visualization/track-comparison** - Grade level performance comparison
   - **GET /api/visualization/grade-trend** - Longitudinal trend analysis
   - **GET /api/visualization/dashboard** - Complete dashboard data aggregation

## ISO 21001 Compliance Features

### Clause 4: Context of the Organization
- **Learner-centered design**: All metrics focus on educational outcomes and learner experience
- **Stakeholder analysis**: Comprehensive demographic filtering and segmentation
- **Risk-based approach**: AI-driven compliance risk assessment and prediction

### Clause 5: Leadership
- **Management commitment**: Full audit trail of all system activities
- **Policy integration**: ISO 21001 principles embedded in validation logic
- **Roles and responsibilities**: Clear separation of concerns through service layer

### Clause 6: Planning
- **Risk and opportunity assessment**: Comprehensive validation identifies improvement areas
- **Quality objectives**: Measurable ISO 21001 indices with benchmarking
- **Continuous improvement planning**: Automated recommendation generation

### Clause 7: Support
- **Resources**: Scalable Laravel architecture with service container
- **Competence**: Built-in validation ensures data quality for decision-making
- **Awareness**: Comprehensive documentation and reporting features
- **Communication**: Multi-format export capabilities for stakeholders
- **Documented information**: Complete audit logging and traceability

### Clause 8: Operation
- **Operational planning**: Structured survey collection with validation
- **Requirements for educational products/services**: ISO 21001 metrics validation
- **Design and development**: Modular service architecture
- **Control of external processes**: Consent validation and privacy controls
- **Learner satisfaction monitoring**: Real-time analytics and sentiment analysis

### Clause 9: Performance Evaluation
- **Monitoring and measurement**: Comprehensive ISO 21001 indices calculation
- **Internal audit**: Automated validation and compliance reporting
- **Management review**: Executive dashboard and analytics reports

### Clause 10: Improvement
- **Nonconformity and corrective action**: Issue detection and recommendation system
- **Continual improvement**: Data-driven insights and trend analysis
- **Customer satisfaction**: Learner feedback analysis and sentiment monitoring

## Data Privacy and Security

### Privacy by Design (ISO 27001 Alignment)
1. **Data Minimization**: Only collects essential ISO 21001 metrics
2. **Encryption**: Automatic encryption of sensitive fields using AES-256
3. **Anonymization**: Anonymous IDs for analytics and reporting
4. **Consent Management**: Explicit consent validation with audit trail
5. **Access Controls**: Role-based access with comprehensive logging

### Technical Security Measures
- **Input Validation**: Comprehensive Laravel validation rules
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- **XSS Protection**: Laravel's built-in security features
- **CSRF Protection**: Laravel middleware protection
- **Rate Limiting**: Configurable rate limiting for API endpoints

## API Endpoints

### Survey Management
```
POST /api/survey/submit
GET  /api/survey/analytics?track=STEM&grade_level=11&academic_year=2024-2025&semester=1st
GET  /api/survey/responses?per_page=15&track=STEM
GET  /api/survey/{id}
DELETE /api/survey/{id}
```

### AI Analysis
```
POST /api/ai/predict-compliance
GET  /api/ai/cluster-responses?k=3&track=STEM
GET  /api/ai/analyze-sentiment?track=STEM
GET  /api/ai/extract-keywords?min_frequency=2
GET  /api/ai/compliance-risk-meter?track=STEM
```

### Visualization
```
GET /api/visualization/bar-chart?track=STEM
GET /api/visualization/pie-chart?grade_level=11
GET /api/visualization/radar-chart?semester=1st
GET /api/visualization/word-cloud?min_frequency=2
GET /api/visualization/track-comparison
GET /api/visualization/grade-trend?academic_year=2024-2025
GET /api/visualization/dashboard?track=STEM
```

### Export and Reporting
```
GET /api/export/excel?track=STEM&grade_level=11
GET /api/export/csv?academic_year=2024-2025
GET /api/export/pdf?semester=1st
GET /api/export/analytics-report?format=pdf&track=STEM
```

## Validation and Quality Assurance

### Comprehensive Validation Suite
1. **Direct vs Indirect Validation**: Correlates self-reported satisfaction with objective performance metrics
2. **Accessibility Compliance**: Validates learning style accommodation and individual support availability
3. **Data Quality Assurance**: Detects missing data, outliers, and temporal anomalies
4. **Statistical Analysis**: Z-score outlier detection and correlation analysis
5. **Privacy Compliance**: Consent validation and data minimization checks

### Compliance Scoring System
- **Overall Compliance Score**: Weighted combination of all validation metrics (0-100)
- **Domain-specific Indices**: Five core ISO 21001 indices (1-5 scale)
- **Risk Assessment**: AI-driven compliance risk prediction with confidence levels
- **Priority Recommendations**: Automated action items based on validation results

## Installation and Setup

### Prerequisites
- PHP 8.1+
- Laravel 10.x
- MySQL 8.0+ or PostgreSQL 13+
- Composer
- Node.js and NPM (for frontend assets)

### Installation Steps
```bash
# Clone the repository
git clone <repository-url>
cd Project-ISO

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database
# Edit .env file with your database credentials

# Run migrations
php artisan migrate

# Install NPM dependencies
npm install

# Compile assets
npm run build

# Seed initial data (optional)
php artisan db:seed --class=AdminSeeder
```

### Database Configuration
Update `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iso_21001_stem
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### API Usage
The system provides a RESTful API with JSON responses. All endpoints require proper authentication except for survey submission (which requires consent validation).

#### Authentication
```bash
# API token authentication (for admin access)
Authorization: Bearer {your-api-token}
```

#### Survey Submission Example
```json
POST /api/survey/submit
Content-Type: application/json

{
    "student_id": "STU123456",
    "track": "STEM",
    "grade_level": 11,
    "academic_year": "2024-2025",
    "semester": "1st",
    "curriculum_relevance_rating": 4,
    "learning_pace_appropriateness": 3,
    "individual_support_availability": 5,
    "learning_style_accommodation": 4,
    "teaching_quality_rating": 4,
    "learning_environment_rating": 5,
    "peer_interaction_satisfaction": 3,
    "extracurricular_satisfaction": 4,
    "academic_progress_rating": 4,
    "skill_development_rating": 5,
    "critical_thinking_improvement": 4,
    "problem_solving_confidence": 4,
    "physical_safety_rating": 5,
    "psychological_safety_rating": 4,
    "bullying_prevention_effectiveness": 5,
    "emergency_preparedness_rating": 4,
    "mental_health_support_rating": 3,
    "stress_management_support": 3,
    "physical_health_support": 4,
    "overall_wellbeing_rating": 4,
    "overall_satisfaction": 4,
    "positive_aspects": "Great support from teachers and modern facilities",
    "improvement_suggestions": "More hands-on lab activities would be beneficial",
    "additional_comments": "Overall positive experience in STEM program",
    "consent_given": true,
    "attendance_rate": 95.5,
    "grade_average": 3.8,
    "participation_score": 88,
    "extracurricular_hours": 12,
    "counseling_sessions": 2
}
```

## Compliance Monitoring

### Regular Validation Schedule
1. **Daily**: Basic data quality checks and outlier detection
2. **Weekly**: Comprehensive ISO 21001 indices calculation
3. **Monthly**: Full compliance reporting and trend analysis
4. **Quarterly**: Accessibility compliance audit
5. **Annually**: Complete ISO 21001 certification preparation

### Key Performance Indicators (KPIs)
- **Learner Needs Index**: ≥4.0/5.0
- **Satisfaction Score**: ≥4.0/5.0
- **Success Index**: ≥4.0/5.0
- **Safety Index**: ≥4.5/5.0 (critical for compliance)
- **Wellbeing Index**: ≥4.0/5.0
- **Data Quality Score**: ≥90/100
- **Consent Compliance Rate**: 100%
- **Accessibility Score**: ≥80/100

### Alert Thresholds
- **Critical Alerts** (Immediate Action): Safety Index <4.0, Consent Rate <100%, Data Quality <80
- **High Priority** (Weekly Review): Any ISO 21001 index <3.5, Accessibility Score <70
- **Medium Priority** (Monthly Review): Correlation discrepancies >20%, Outliers >5%
- **Low Priority** (Quarterly Review): Temporal anomalies, Minor data quality issues

## Maintenance and Support

### System Requirements
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP Extensions**: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Database**: MySQL 8.0+ with InnoDB engine
- **Memory**: Minimum 512MB PHP memory limit
- **Storage**: 100MB+ for application data and logs

### Update Process
```bash
# Backup database and files
mysqldump -u username -p iso_21001_stem > backup.sql
tar -czf backup.tar.gz .

# Pull latest changes
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Restart services (if using queue workers)
php artisan queue:restart
```

### Troubleshooting

#### Common Issues
1. **Survey Submission Fails**: Check validation rules and consent field
2. **Export Generation Slow**: Increase PHP memory limit and execution time
3. **AI Analysis Errors**: Verify PhpML library installation and data availability
4. **PDF Generation Issues**: Ensure Dompdf library is properly configured
5. **Database Connection Errors**: Verify .env database configuration

#### Log Locations
- **Application Logs**: `storage/logs/laravel.log`
- **Audit Logs**: Database `audit_logs` table
- **Validation Logs**: Monitored through ValidationService logging
- **Queue Logs**: `storage/logs/horizon.log` (if using Laravel Horizon)

### Support Contact
For technical support and ISO 21001 compliance assistance:
- **Email**: support@project-iso.com
- **Documentation**: https://docs.project-iso.com
- **Issue Tracker**: https://github.com/your-org/project-iso/issues

## Appendix A: ISO 21001 Mapping

### Key Clauses Implemented

**Clause 4.1 Understanding the organization and its context**
- Stakeholder analysis through demographic filtering
- Risk assessment through AI compliance prediction

**Clause 5.1.2 Top management**
- Comprehensive audit trail for management oversight
- Executive reporting and dashboard capabilities

**Clause 6.2 Educational quality objectives**
- Measurable ISO 21001 indices with benchmarking
- Continuous monitoring and trend analysis

**Clause 7.1.4 Resources for educational processes**
- Learning style accommodation validation
- Individual support availability monitoring

**Clause 7.2.3 Learner wellbeing**
- Comprehensive wellbeing metrics collection
- Mental health support effectiveness assessment

**Clause 7.4.1 Learner information management**
- Privacy by design with encryption and anonymization
- Consent management and validation

**Clause 8.2.4 Monitoring and measurement of processes**
- Real-time ISO 21001 indices calculation
- Automated validation and quality assurance

**Clause 9.1.3 Analysis and evaluation**
- Statistical correlation analysis
- AI-driven pattern recognition and clustering

**Clause 10.2 Nonconformity and corrective action**
- Automated issue detection and prioritization
- Actionable recommendation generation

### Compliance Certification Preparation

1. **Internal Audit Checklist**
   - [ ] All ISO 21001 metrics collected and validated
   - [ ] Privacy and consent compliance verified
   - [ ] Accessibility requirements met
   - [ ] Continuous improvement processes established
   - [ ] Management review documentation complete

2. **Required Documentation**
   - [ ] Quality policy aligned with ISO 21001
   - [ ] Risk and opportunity register
   - [ ] Internal audit reports and findings
   - [ ] Management review meeting minutes
   - [ ] Corrective action implementation records

This system provides comprehensive support for ISO 21001 compliance while maintaining focus on educational quality improvement and learner-centered principles.