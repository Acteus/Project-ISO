# Student Survey System Backend

A comprehensive backend system for collecting and analyzing student survey responses, built with PHP and Laravel. This system provides secure data collection, AI-powered analytics, and comprehensive reporting capabilities.

## Features

### Core Functionality
- Secure survey response collection with validation
- Admin authentication and authorization
- Real-time analytics and reporting
- Data export in multiple formats (Excel, CSV, PDF)
- Audit logging for compliance

### AI-Powered Features
- Compliance prediction using machine learning
- Response clustering analysis
- Sentiment analysis from student comments
- Keyword extraction and topic modeling
- Risk assessment with traffic light system

### Data Visualization
- Bar charts for rating distributions
- Pie charts for response analysis
- Radar charts for performance metrics
- Word clouds from student feedback
- Program comparison analytics

### Security & Compliance
- Data encryption for sensitive information
- Consent management for Data Privacy Act compliance
- Duplicate submission prevention
- Anonymous data processing for analytics
- Comprehensive audit trails

## Requirements

- PHP 8.1 or higher
- Composer
- SQLite (or MySQL/PostgreSQL)
- Laravel 12

## Installation

1. Clone the repository:
```bash
git clone https://github.com/your-username/student-survey-system.git
cd student-survey-system
```

2. Install PHP dependencies:
```bash
composer install
```

3. Copy the environment file and configure it:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/your/database/database.sqlite
```

5. Run database migrations:
```bash
php artisan migrate
```

6. Seed the database with an admin user:
```bash
php artisan db:seed
```

## Configuration

### Database Setup
The system supports SQLite, MySQL, and PostgreSQL. Configure your database settings in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_survey
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Environment Variables
Key configuration options:

- `APP_NAME`: Application name
- `APP_ENV`: Environment (local, production)
- `APP_DEBUG`: Debug mode
- `DB_CONNECTION`: Database connection type
- `SANCTUM_STATEFUL_DOMAINS`: Domains for Sanctum authentication

## Usage

### Starting the Server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

### Default Admin Credentials
After seeding the database, you can login with:
- Email: `admin@example.com`
- Password: `password`

## API Documentation

### Authentication Endpoints

#### Login
```http
POST /api/admin/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password"
}
```

#### Logout
```http
POST /api/admin/logout
Authorization: Bearer {token}
```

#### Get Profile
```http
GET /api/admin/me
Authorization: Bearer {token}
```

### Survey Endpoints

#### Submit Survey Response
```http
POST /api/survey/submit
Content-Type: application/json

{
    "student_number": "123456789",
    "program": "BSIT",
    "year_level": 3,
    "course_content_rating": 4,
    "facilities_rating": 4,
    "support_services_rating": 4,
    "overall_satisfaction": 4,
    "comments": "Good experience",
    "consent_given": true
}
```

#### Get Analytics
```http
GET /api/survey/analytics?program=BSIT&year_level=3
Authorization: Bearer {token}
```

### AI Endpoints

#### Compliance Prediction
```http
POST /api/ai/compliance-predict
Authorization: Bearer {token}
Content-Type: application/json

{
    "course_content_rating": 4,
    "facilities_rating": 4,
    "support_services_rating": 4,
    "overall_satisfaction": 4
}
```

#### Cluster Analysis
```http
GET /api/ai/cluster-responses?program=BSIT&clusters=3
Authorization: Bearer {token}
```

#### Sentiment Analysis
```http
GET /api/ai/sentiment-analysis
Authorization: Bearer {token}
```

### Visualization Endpoints

#### Bar Chart Data
```http
GET /api/visualizations/bar-chart?program=BSIT
Authorization: Bearer {token}
```

#### Pie Chart Data
```http
GET /api/visualizations/pie-chart?program=BSIT
Authorization: Bearer {token}
```

#### Word Cloud Data
```http
GET /api/visualizations/word-cloud?min_frequency=2
Authorization: Bearer {token}
```

### Export Endpoints

#### Export to Excel
```http
GET /api/export/excel?program=BSIT&year_level=3
Authorization: Bearer {token}
```

#### Export to CSV
```http
GET /api/export/csv?program=BSIT&year_level=3
Authorization: Bearer {token}
```

#### Export PDF Report
```http
GET /api/export/pdf?program=BSIT&year_level=3
Authorization: Bearer {token}
```

## Testing

Run the test suite:
```bash
php artisan test
```

Run specific test:
```bash
php artisan test --filter AdminAuthenticationTest
```

## Security

### Data Privacy
- Student numbers and comments are encrypted in the database
- Anonymous IDs are used for analytics
- Consent is required for survey submission
- Data retention follows privacy regulations

### Authentication
- Token-based authentication using Laravel Sanctum
- Secure password hashing
- Session management with automatic expiration

### Audit Logging
- All admin activities are logged
- IP addresses are tracked
- Login/logout events are recorded
- Data access is monitored

## Data Structure

### Survey Response Fields
- `student_number`: Encrypted student identifier
- `program`: BSIT or BSIT-BA
- `year_level`: 1-4
- `course_content_rating`: 1-5 scale
- `facilities_rating`: 1-5 scale
- `support_services_rating`: 1-5 scale
- `overall_satisfaction`: 1-5 scale
- `comments`: Encrypted feedback text
- `consent_given`: Boolean consent flag
- `ip_address`: IP address for security tracking

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions, please open an issue on GitHub or contact the development team.
