# Jose Rizal University - ISO 21001 Student Survey System

A comprehensive Laravel-based survey system designed for Jose Rizal University to achieve and maintain compliance with [ISO 21001:2018 - Educational organizations — Management systems for educational organizations — Requirements with guidance for use](https://www.iso.org/standard/66266.html). This system provides learner-centered survey collection, AI-powered compliance analysis, and comprehensive quality management reporting.

## Overview

This application implements a complete ISO 21001 quality management system for educational institutions, specifically tailored for Computer System Servicing (CSS) students. It features:

- **ISO 21001 Compliance**: Full compliance with educational quality management standards
- **Learner-Centric Design**: Comprehensive survey metrics across 5 key domains
- **AI-Powered Analytics**: Machine learning for compliance prediction and insights with Flask AI service and PHP-ML fallback
- **Privacy by Design**: GDPR-compliant data handling with encryption and anonymization
- **Comprehensive Reporting**: Multi-format exports and executive dashboards
- **Audit Trail**: Complete traceability for compliance verification
- **QR Code Management**: Automated QR code generation and tracking for survey distribution
- **Weekly Progress Tracking**: Automated weekly metrics aggregation and goal management
- **Email Notifications**: Automated weekly and monthly compliance reports
- **Advanced Analytics**: Real-time dashboards with interactive visualizations

## Architecture Overview

This is a monolithic Laravel application with a service-oriented architecture and integrated Python AI service:

### Core Components
- **Laravel Framework**: Version 12 with PHP 8.2+
- **Database**: SQLite/MySQL/PostgreSQL support
- **Authentication**: Laravel Sanctum for API authentication and session-based auth
- **Frontend**: Laravel Blade views with Tailwind CSS and vanilla JavaScript (Axios for API calls)
- **AI/ML**:
  - **Primary**: Python Flask AI Service with TensorFlow/scikit-learn (8 ML models)
  - **Fallback**: PHP-ML library for offline compliance analysis
  - **Circuit Breaker**: Resilient service communication with retry mechanisms
- **Exports**: Laravel Excel and DomPDF for reporting
- **Visualization**: Chart.js for real-time analytics dashboards
- **QR Code Management**: SimpleSoftwareIO QR Code library for survey distribution
- **Email**: Resend for automated notifications and reports
- **Caching**: Advanced caching with Redis/file cache support
- **Background Jobs**: Laravel Queue for automated report generation

### Key Features
- Student registration and authentication system with email verification
- Comprehensive ISO 21001 survey forms (20+ questions across 5 domains)
- Admin dashboard with real-time analytics and interactive visualizations
- AI-driven compliance risk assessment with 8 ML models
- QR code generation and tracking for survey distribution
- Weekly progress tracking and automated goal management
- Multi-format data exports (Excel, CSV, PDF)
- Automated email reports (weekly/monthly compliance reports)
- Complete audit logging and GDPR-compliant privacy controls
- Advanced caching and performance optimization

## Project Structure

```
project-iso/
├── app/                          # Laravel application code
│   ├── Console/Commands/         # Artisan commands for automation
│   │   ├── AggregateWeeklyMetrics.php    # Weekly metrics aggregation
│   │   ├── GenerateMonthlyReports.php    # Automated report generation
│   │   ├── SendWeeklyProgressReports.php # Email notifications
│   │   └── TestFlaskAIService.php        # AI service testing
│   ├── Http/Controllers/         # Controllers (Survey, AI, Admin, etc.)
│   │   ├── AIController.php              # AI insights and predictions
│   │   ├── AdminAuthController.php       # Admin authentication
│   │   ├── AnalyticsController.php       # Analytics and reporting
│   │   ├── ExportController.php          # Data export functionality
│   │   ├── QrCodeController.php          # QR code management
│   │   ├── StudentController.php         # Student authentication & dashboard
│   │   ├── SurveyController.php          # Survey management
│   │   └── VisualizationController.php   # Chart and visualization data
│   ├── Models/                   # Eloquent models
│   │   ├── Admin.php             # Admin user model
│   │   ├── AuditLog.php          # Audit logging
│   │   ├── Goal.php              # Goal tracking
│   │   ├── QrCode.php            # QR code management
│   │   ├── SurveyResponse.php    # Survey response data
│   │   ├── User.php              # Student user model
│   │   └── WeeklyMetric.php      # Weekly progress metrics
│   ├── Services/                 # Business logic services
│   │   ├── AIService.php         # Core AI service with fallback logic
│   │   ├── AnalyticsService.php  # Analytics calculations
│   │   ├── CacheService.php      # Advanced caching
│   │   ├── FlaskAIClient.php     # Flask AI service client
│   │   ├── QrCodeService.php     # QR code generation
│   │   └── VisualizationService.php # Chart data generation
│   ├── Exports/                  # Export classes
│   └── Observers/                # Model observers
├── config/                       # Configuration files
│   ├── ai.php                    # AI service configuration
│   ├── performance.php           # Performance optimization settings
│   └── queue.php                 # Background job configuration
├── database/                     # Migrations and seeders
│   ├── migrations/               # Database schema migrations
│   │   ├── 2025_08_28_080733_create_survey_responses_table.php
│   │   ├── 2025_10_28_185339_create_weekly_metrics_table.php
│   │   ├── 2025_10_28_190344_create_goals_table.php
│   │   ├── 2025_10_29_215050_create_qr_codes_table.php
│   │   └── ...additional migrations
│   └── seeders/                  # Data seeding
├── docs/                         # Documentation
│   ├── iso-21001-system-documentation.md
│   ├── analytics-enhancements.md
│   └── qr-code-documentation.md
├── resources/views/              # Blade templates
│   ├── admin/                    # Admin dashboard views
│   │   ├── dashboard.blade.php
│   │   ├── ai-insights.blade.php
│   │   ├── qr-codes/
│   │   └── reports.blade.php
│   ├── student/                  # Student-facing views
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   └── dashboard.blade.php
│   └── survey/                   # Survey forms and pages
│       ├── form.blade.php
│       ├── landing.blade.php
│       └── thankyou.blade.php
├── routes/                       # Route definitions
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
└── storage/                      # File storage and logs
├── bootstrap/                    # Laravel bootstrap files
├── config/                       # Configuration files
│   └── ai.php                    # AI service configuration
├── database/                     # Migrations and seeders
│   ├── migrations/               # Database schema
│   └── seeders/                  # Data seeding
├── public/                       # Public assets (CSS, JS, images)
├── resources/                    # Blade views and frontend assets
│   ├── css/                      # Stylesheets
│   ├── js/                       # JavaScript files
│   └── views/                    # Blade templates
│       └── admin/
│           ├── dashboard.blade.php       # Admin dashboard
│           └── ai-insights.blade.php     # AI insights dashboard
├── routes/                       # Route definitions
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
├── storage/                      # File storage and logs
├── tests/                        # Test suites
└── docs/                         # Documentation
```

## ISO 21001 Compliance Features

### Core Domains Assessed
1. **Learner Needs Assessment** (Curriculum relevance, learning pace, individual support, learning style accommodation)
2. **Learner Satisfaction Metrics** (Teaching quality, learning environment, peer interaction, extracurricular activities)
3. **Learner Success Indicators** (Academic progress, skill development, critical thinking, problem solving)
4. **Learner Safety Assessment** (Physical safety, psychological safety, bullying prevention, emergency preparedness)
5. **Learner Wellbeing Metrics** (Mental health support, stress management, physical health, overall wellbeing)

### AI-Powered Features
- **8 Advanced ML Models**:
  - Compliance Prediction (Flask service with PHP-ML fallback)
  - Sentiment Analysis (NLP with keyword-based fallback)
  - Student Clustering (K-Means clustering with PHP-ML)
  - Performance Prediction (Weighted algorithm with ML fallback)
  - Dropout Risk Assessment (Rule-based with ML enhancement)
  - Comprehensive Risk Assessment (Multi-dimensional analysis)
  - Satisfaction Trend Analysis (Time series forecasting)
  - Keyword Extraction (TF-IDF analysis)
- **Real-time AI Insights Dashboard**: Interactive dashboard with live metrics and predictions
- **Automatic Fallback System**: PHP-ML backup when Flask service unavailable
- **Circuit Breaker Pattern**: Resilient service communication with retry mechanisms
- **Service Health Monitoring**: Real-time status tracking with 6 key metrics

### Data Privacy & Security
- **Encryption**: AES-256 encryption for sensitive student data
- **Anonymization**: SHA-256 anonymous IDs for analytics
- **Consent Management**: Explicit consent validation with audit trail
- **Audit Logging**: Complete traceability of all system activities
- **GDPR Compliance**: Privacy by design principles throughout

## Key Features

### Survey System
- **Comprehensive ISO 21001 Metrics**: 20+ survey questions covering all 5 learner domains
- **Student Authentication**: Secure login/registration system for CSS students
- **Real-time Validation**: Client and server-side validation with detailed error messages
- **Progress Tracking**: Survey completion indicators and session management
- **Consent Management**: Explicit consent collection with legal compliance

### Analytics & Reporting
- **ISO 21001 Indices**: Automated calculation of 5 core compliance indices
- **Correlation Analysis**: Statistical analysis between satisfaction and performance metrics
- **Demographic Filtering**: Analytics by track, grade level, academic year, and semester
- **Multi-format Exports**: Excel, CSV, and PDF reports for accreditation
- **Dashboard Visualization**: Interactive charts and performance metrics

### AI & Machine Learning
- **Flask AI Service Integration**: Python-based microservice with 8 ML models
- **Real-time Analytics Dashboard**: `/admin/ai-insights` with live predictions
- **Comprehensive Risk Assessment**: Multi-dimensional ISO 21001 compliance analysis
- **Predictive Performance Modeling**: Early identification of at-risk students
- **Sentiment & NLP Analysis**: Advanced text analysis of student feedback
- **Student Segmentation**: Intelligent clustering for targeted interventions
- **Trend Forecasting**: Time series analysis for proactive quality management
- **Service Health Monitoring**: Real-time status tracking with 6 key metrics

### Administration
- **Admin Dashboard**: Comprehensive analytics and management interface
- **User Management**: Student and admin account management with email verification
- **QR Code Management**: Generate, track, and manage survey distribution QR codes
- **Goal Management**: Set and track ISO 21001 compliance goals and targets
- **Weekly Progress Tracking**: Automated weekly metrics aggregation and reporting
- **Audit Logging**: Complete system activity tracking for compliance
- **Data Privacy**: GDPR-compliant data handling and anonymization
- **Export Management**: Secure data export with privacy controls
- **Email Reports**: Automated weekly and monthly compliance reports

## Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Database**: SQLite (default), MySQL, or PostgreSQL
- **Laravel**: Version 12
- **Node.js**: 18+ (for Vite asset compilation)
- **npm**: Latest version
- **Python**: 3.8+ (for Flask AI service, optional)
- **Redis**: (optional, for enhanced caching)

## Installation

1. **Clone the repository:**
```bash
git clone <repository-url>
cd project-iso
```

2. **Install PHP dependencies:**
```bash
composer install --optimize-autoloader --no-dev
```

3. **Install Node.js dependencies:**
```bash
npm install
```

4. **Environment setup:**
```bash
cp .env.example .env
php artisan key:generate
```

**Note:** The `.env.example` file contains all the necessary environment variables for the application, including AI service configuration, database settings, and other required parameters.

5. **Database configuration:**
Update `.env` file with your database credentials:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# Or for MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iso_21001_survey
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Database setup:**
```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

7. **Build frontend assets:**
```bash
npm run build
```

8. **Start Flask AI Service (Optional but Recommended):**
```bash
# The Flask AI service can be deployed separately
# Source code available at: https://github.com/Acteus/ai-service/tree/main
# Configure the service URL in your .env file:
FLASK_AI_SERVICE_URL=https://your-ai-service-url.com
```

The system will work with PHP-ML fallback if Flask service is not running.

### Database Setup
The system supports multiple database backends:

**SQLite (Recommended for development):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/Users/gdullas/Desktop/Projects/Kwadra/Project-ISO/database/database.sqlite
```

**MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iso_21001_survey
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=iso_21001_survey
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Usage

### Starting the Application

1. **Development Server:**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
Access at: `http://localhost:8000`

2. **Development with Asset Watching:**
```bash
npm run dev
```

3. **Production Deployment:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Default Credentials

**Admin Access:**
- Email: `admin@example.com`
- Password: `password`
- Access URL: `/student/login` → Admin Dashboard

**Student Registration:**
- Students can register at `/student/register`
- After registration, login at `/student/login`

### Application Flow

1. **Welcome Page** (`/`): University branding and entry point
2. **Student Registration** (`/student/register`): Account creation with email verification
3. **Email Verification** (`/email/verify`): Email verification process
4. **Student Login** (`/student/login`): Secure authentication
5. **Survey Landing** (`/home`): Survey introduction and instructions
6. **Survey Form** (`/survey`): ISO 21001 survey completion (20+ questions)
7. **Thank You** (`/thank-you`): Survey completion confirmation
8. **Student Dashboard** (`/student/dashboard`): Personal survey status
9. **Admin Dashboard** (`/admin/dashboard`): Comprehensive analytics and management
10. **AI Insights Dashboard** (`/admin/ai-insights`): Advanced ML analytics with 8 models
11. **QR Code Management** (`/admin/qr-codes`): Generate and track survey QR codes
12. **Goal Management** (`/admin/goals`): Set and track compliance targets
13. **Reports** (`/admin/reports`): Automated weekly and monthly reports

## API Documentation

### Authentication Endpoints

#### Admin Login
```http
POST /api/admin/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password"
}
```

#### Admin Logout
```http
POST /api/admin/logout
Authorization: Bearer {token}
```

#### Get Admin Profile
```http
GET /api/admin/me
Authorization: Bearer {token}
```

### Survey Management

#### Submit Survey Response
```http
POST /api/survey/submit
Content-Type: application/json

{
    "student_id": "CSS2025001",
    "track": "CSS",
    "grade_level": 11,
    "academic_year": "2024-2025",
    "semester": "1st",
    "gender": "Male",
    "curriculum_relevance_rating": 4,
    "learning_pace_appropriateness": 4,
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
    "positive_aspects": "Great teaching quality and modern facilities",
    "improvement_suggestions": "More hands-on lab activities needed",
    "additional_comments": "Overall positive experience",
    "consent_given": true,
    "attendance_rate": 95.5,
    "grade_average": 3.8,
    "participation_score": 88,
    "extracurricular_hours": 12,
    "counseling_sessions": 2
}
```

#### Get Survey Analytics (New Simplified API)
```http
GET /api/analytics/summary?track=CSS&grade_level=11&academic_year=2024-2025&semester=1st
Authorization: Bearer {token}
```

#### Get Time Series Data
```http
GET /api/analytics/time-series?metric=overall_satisfaction&groupBy=week&filters[track]=CSS
Authorization: Bearer {token}
```

#### Get Compliance Data
```http
GET /api/analytics/compliance?dateFrom=2024-01-01&dateTo=2024-12-31
Authorization: Bearer {token}
```

#### Get Survey Analytics
```http
GET /api/survey/analytics?track=CSS&grade_level=11&academic_year=2024-2025&semester=1st
Authorization: Bearer {token}
```

#### Get Survey Responses
```http
GET /api/survey/responses?per_page=15&track=CSS
Authorization: Bearer {token}
```

### AI & Analytics Endpoints

#### Compliance Prediction
```http
POST /api/ai/predict-compliance
Authorization: Bearer {token}
Content-Type: application/json

{
    "curriculum_relevance_rating": 4,
    "learning_pace_appropriateness": 4,
    "teaching_quality_rating": 4,
    "overall_satisfaction": 4
}
```

#### Cluster Analysis
```http
GET /api/ai/cluster-responses?k=3&track=CSS
Authorization: Bearer {token}
```

#### Sentiment Analysis
```http
GET /api/ai/analyze-sentiment?track=CSS
Authorization: Bearer {token}
```

### Visualization Endpoints

#### Bar Chart Data
```http
GET /api/visualization/bar-chart?track=CSS
Authorization: Bearer {token}
```

#### Pie Chart Data
```http
GET /api/visualization/pie-chart?grade_level=11
Authorization: Bearer {token}
```

#### Word Cloud Data
```http
GET /api/visualization/word-cloud?min_frequency=2
Authorization: Bearer {token}
```

### Export Endpoints

#### Export to Excel
```http
GET /api/export/excel?track=CSS&grade_level=11
Authorization: Bearer {token}
```

#### Export to CSV
```http
GET /api/export/csv?academic_year=2024-2025
Authorization: Bearer {token}
```

#### Export PDF Report
```http
GET /api/export/pdf?semester=1st
Authorization: Bearer {token}
```

#### Export Analytics Report
```http
GET /api/export/analytics-report?dateFrom=2024-01-01&dateTo=2024-12-31
Authorization: Bearer {token}
```

### QR Code Management Endpoints

#### List QR Codes
```http
GET /admin/qr-codes
Authorization: Bearer {token}
```

#### Create QR Code
```http
POST /admin/qr-codes
Content-Type: application/json

{
    "name": "Grade 11 CSS Survey",
    "target_url": "https://survey.example.com/survey",
    "track": "CSS",
    "grade_level": "11",
    "academic_year": "2024-2025",
    "semester": "1st"
}
```

#### Batch Generate QR Codes
```http
POST /admin/qr-codes/batch-generate
Content-Type: application/json

{
    "configs": [
        {
            "track": "CSS",
            "grade_level": "11",
            "section": "A"
        }
    ]
}
```

### Goal Management Endpoints

#### List Goals
```http
GET /admin/goals
Authorization: Bearer {token}
```

#### Create Goal
```http
POST /admin/goals
Content-Type: application/json

{
    "name": "Improve Satisfaction Score",
    "metric_type": "satisfaction",
    "target_value": 4.2,
    "target_date": "2024-12-31"
}
```

#### Update Goal Progress
```http
POST /admin/goals/{id}/progress
Content-Type: application/json

{
    "current_value": 4.1,
    "notes": "Weekly progress update"
}
```

## Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test --filter AdminAuthenticationTest

# Run ISO 21001 compliance tests
php artisan test --filter ISO21001ComplianceTest

# Run with coverage
php artisan test --coverage
```

### Test Structure
- **Feature Tests**: API endpoint testing (`tests/Feature/`)
- **Unit Tests**: Individual component testing (`tests/Unit/`)
- **ISO 21001 Tests**: Compliance validation testing

## Artisan Commands

### AI Service Testing
```bash
# Test Flask AI service connectivity
php artisan ai:test-flask

# Test specific AI features
php artisan ai:test-flask --compliance  # Test compliance prediction
php artisan ai:test-flask --sentiment   # Test sentiment analysis
php artisan ai:test-flask --service-only # Test service health only
```

### Data Management
```bash
# Aggregate weekly metrics
php artisan weekly:aggregate

# Generate monthly reports
php artisan reports:generate-monthly

# Send weekly progress reports
php artisan reports:send-weekly

# Export survey data for AI training
php artisan export:survey-data-for-training
```

### Cache Management
```bash
# Clear all caches
php artisan cache:clear-custom

# Warm up caches
php artisan cache:warmup

# View cache statistics
php artisan cache:stats
```

### System Diagnostics
```bash
# Diagnose storage permissions
php artisan storage:diagnose

# Fix storage permissions
php artisan storage:fix-permissions

# Test email configuration
php artisan email:test-config
```

## Security & Compliance

### Data Privacy (GDPR & ISO 27001)
- **Encryption**: AES-256 encryption for sensitive student data (student_id, comments)
- **Anonymization**: SHA-256 anonymous IDs for analytics and reporting
- **Consent Management**: Explicit consent validation with audit trail
- **Data Minimization**: Only essential ISO 21001 metrics collected
- **Retention Policies**: Configurable data retention periods

### Authentication & Authorization
- **Laravel Sanctum**: Token-based API authentication
- **Role-Based Access**: Student and admin role separation
- **Session Security**: Secure session management with expiration
- **Password Security**: Bcrypt hashing with salt

### Audit & Compliance
- **Complete Audit Trail**: All system activities logged with timestamps and IP addresses
- **ISO 21001 Traceability**: Full compliance with Clause 8.2.4 (Traceability)
- **Access Monitoring**: All data access events tracked
- **Compliance Reporting**: Automated compliance status reporting

## Data Structure

### Survey Response Model
The `SurveyResponse` model captures comprehensive ISO 21001 metrics:

#### Student Information
- `student_id`: Encrypted student identifier
- `track`: CSS (Computer System Servicing)
- `grade_level`: 11 or 12
- `academic_year`: e.g., "2024-2025"
- `semester`: "1st" or "2nd"
- `gender`: Optional demographic data

### Additional Models

#### WeeklyMetric Model
Tracks weekly aggregated metrics for progress monitoring:
- `week_start_date`, `week_end_date`: Date range for the week
- `total_responses`, `new_responses`: Response counts
- `learner_needs_index`, `satisfaction_score`, etc.: ISO 21001 indices
- `compliance_score`, `risk_level`: Compliance tracking
- `target_satisfaction`, `target_compliance`: Goal tracking

#### Goal Model
Manages compliance goals and targets:
- `name`, `description`: Goal details
- `metric_type`: Type of metric being tracked
- `target_value`, `current_value`: Goal progress
- `status`: active, achieved, expired, cancelled
- `progress_history`: JSON tracking of progress over time

#### QrCode Model
Manages QR codes for survey distribution:
- `name`, `description`: QR code identification
- `target_url`, `file_path`: URL and storage path
- `track`, `grade_level`, `section`: Targeting information
- `scan_count`, `scan_analytics`: Usage tracking
- `expires_at`, `is_active`: Expiration and status

#### AuditLog Model
Complete audit trail for compliance:
- `user_id`, `action`: Who did what
- `model_type`, `model_id`: What was affected
- `old_values`, `new_values`: Before/after data
- `ip_address`, `user_agent`: Request details

#### ISO 21001 Learner Needs Assessment (1-5 scale)
- `curriculum_relevance_rating`: Relevance to career goals
- `learning_pace_appropriateness`: Pace matches learning needs
- `individual_support_availability`: Support for individual needs
- `learning_style_accommodation`: Accommodation for different learning styles

#### ISO 21001 Learner Satisfaction Metrics (1-5 scale)
- `teaching_quality_rating`: Quality of instruction
- `learning_environment_rating`: Physical and virtual learning spaces
- `peer_interaction_satisfaction`: Quality of peer interactions
- `extracurricular_satisfaction`: Extracurricular activities

#### ISO 21001 Learner Success Indicators (1-5 scale)
- `academic_progress_rating`: Academic achievement
- `skill_development_rating`: Technical skill development
- `critical_thinking_improvement`: Critical thinking growth
- `problem_solving_confidence`: Problem-solving confidence

#### ISO 21001 Learner Safety Assessment (1-5 scale)
- `physical_safety_rating`: Physical safety measures
- `psychological_safety_rating`: Psychological safety environment
- `bullying_prevention_effectiveness`: Bullying prevention measures
- `emergency_preparedness_rating`: Emergency preparedness

#### ISO 21001 Learner Wellbeing Metrics (1-5 scale)
- `mental_health_support_rating`: Mental health support availability
- `stress_management_support`: Stress management resources
- `physical_health_support`: Physical health support
- `overall_wellbeing_rating`: Overall wellbeing

#### Qualitative Feedback
- `positive_aspects`: What students like (encrypted)
- `improvement_suggestions`: Areas for improvement (encrypted)
- `additional_comments`: General feedback (encrypted)

#### Privacy & Consent
- `consent_given`: Boolean consent flag (required)
- `ip_address`: IP address for audit purposes

#### Indirect Performance Metrics
- `attendance_rate`: Percentage attendance
- `grade_average`: GPA on 4.0 scale
- `participation_score`: Class participation score
- `extracurricular_hours`: Hours in extracurricular activities
- `counseling_sessions`: Number of counseling sessions attended

## Development & Deployment

### Development Workflow
```bash
# Start development server
php artisan serve --host=0.0.0.0 --port=8000

# Watch and compile assets
npm run dev

# Run tests
php artisan test

# Check code quality
./vendor/bin/pint

# Test AI service connection
php artisan ai:test-flask

# Aggregate weekly metrics
php artisan weekly:aggregate
```

### Production Deployment
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan optimize

# Warm up caches
php artisan cache:warmup
```

### Background Jobs & Automation
```bash
# Process queues for background tasks
php artisan queue:work

# Schedule automated tasks (add to crontab)
# * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

# Weekly automation (run every Monday)
php artisan weekly:aggregate
php artisan reports:send-weekly

# Monthly automation (run on 1st of month)
php artisan reports:generate-monthly
```

### Automated Tasks
The system includes several automated tasks that run via Laravel's scheduler:

- **Weekly Metrics Aggregation**: Every Monday at 9 AM
- **Weekly Progress Reports**: Every Monday at 10 AM
- **Monthly Compliance Reports**: 1st of every month at 9 AM
- **Cache Warmup**: Daily at 2 AM
- **AI Service Health Checks**: Every 15 minutes

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/iso21001-enhancement`)
3. Follow Laravel coding standards and ISO 21001 principles
4. Add tests for new features
5. Commit with descriptive messages (`git commit -m 'Add ISO 21001 compliance validation'`)
6. Push to the branch (`git push origin feature/iso21001-enhancement`)
7. Open a Pull Request

## Documentation

- **ISO 21001 System Documentation**: `docs/iso-21001-system-documentation.md`
- **Data Model Documentation**: `docs/stem-data-model.md`
- **Frontend Documentation**: `frontend-docs.md`
- **Flask AI Integration**: `README-FLAK-AI-INTEGRATION.md`
- **AI Service Documentation**: `ai-service/README.md`
- **API Documentation**: See API section above

## AI Service Commands

```bash
# Test Flask AI service connectivity
php artisan ai:test-flask

# Test specific features
php artisan ai:test-flask --compliance  # Test compliance prediction
php artisan ai:test-flask --sentiment   # Test sentiment analysis
php artisan ai:test-flask --service-only # Test service health only

# Test AI service connection (alternative command)
php artisan ai:test-connection
```

## Additional Documentation

- **ISO 21001 System Documentation**: `docs/iso-21001-system-documentation.md`
- **Analytics Enhancements**: `docs/analytics-enhancements.md`
- **QR Code Documentation**: `docs/qr-code-documentation.md`
- **STEM Data Model**: `docs/stem-data-model.md`
- **Deployment Guide**: `DEPLOYMENT_README.md`
- **Email Setup Guide**: `EMAIL-SETUP-GUIDE.md`

## License

This project is developed for Jose Rizal University to achieve ISO 21001:2018 compliance. All rights reserved.

## Support

For technical support and ISO 21001 compliance assistance:
- **Email**: support@jru.edu.ph
- **Documentation**: Internal university documentation system
- **Development Team**: University IT Department

---

**ISO 21001 Compliance Status**: This system implements comprehensive ISO 21001:2018 educational quality management standards for learner-centered education assessment and continuous improvement.
