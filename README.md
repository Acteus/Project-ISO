# Jose Rizal University – ISO 21001 Student Survey Platform

![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel) ![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php) ![Node.js](https://img.shields.io/badge/Node.js-18%2B-339933?logo=node.js) ![TailwindCSS 4](https://img.shields.io/badge/TailwindCSS-4.0-38B2AC?logo=tailwind-css) ![Vite 7](https://img.shields.io/badge/Vite-7.0-646CFF?logo=vite)

![AI Service](https://img.shields.io/badge/AI_Service-Flask%20%2B%20PHP--ML-000000?logo=flask) ![Last Commit](https://img.shields.io/badge/last_commit-2025--11--07-orange)

![Blade LOC](https://img.shields.io/badge/Blade-21k_LOC-FF2D20) ![PHP LOC](https://img.shields.io/badge/PHP-16k_LOC-777BB4?logo=php) ![JavaScript LOC](https://img.shields.io/badge/JavaScript-3.5k_LOC-F7DF1E?logo=javascript)

A production-aligned Laravel 12 platform that centralizes ISO 21001 data collection, analytics, and accreditation reporting for Computer System Servicing (CSS) learners at Jose Rizal University. The system combines real-time dashboards, AI-assisted compliance scoring, and privacy-by-design safeguards to support continual improvement cycles.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Architecture](#architecture)
- [Installation & Setup](#installation--setup)
  - [Prerequisites](#prerequisites)
  - [Quick Start](#quick-start)
- [Configuration](#configuration)
  - [Environment Variables](#environment-variables)
  - [Service Configuration](#service-configuration)
- [Usage & Operations](#usage--operations)
  - [Scheduled Commands](#scheduled-commands)
  - [Admin Features](#admin-features)
  - [Queue Workers](#queue-workers)
- [Development](#development)
  - [Testing](#testing)
  - [Code Quality](#code-quality)
- [Documentation](#documentation)
- [Support](#support)

---

## Overview

This platform provides a comprehensive solution for ISO 21001 compliance management, featuring:

- **Survey Management**: ISO 21001-aligned survey intake, analytics, and reporting tailored for CSS learners and administrators
- **AI-Powered Analytics**: AI-assisted compliance scoring via a Flask microservice (`App\Services\FlaskAIClient`) with PHP-ML fallback logic
- **Privacy-First Design**: Consent-aware workflow that prevents duplicate submissions and auto-validates prior consent for authenticated students
- **Admin Workspace**: Live dashboards, QR distribution, indirect metrics ingestion, and export tooling
- **Automated Reporting**: Weekly and monthly compliance reports, audit trails, and multi-format exports for accreditation evidence

---

## Features

### Survey Intake
- Consent enforcement and validation
- Data minimization principles
- Indirect metrics support
- Encrypted storage with duplicate-response detection
- Controller: `SurveyController`

### Analytics & AI
- Cached dashboards for performance
- Compliance scoring automation
- Data clustering and sentiment analysis
- Keyword extraction
- Controllers/Services: `AnalyticsController`, `VisualizationService`, `AIService`

### Governance & Privacy
- Complete audit trail with `AuditService` and `AuditMiddleware`
- Consent logging via `ConsentService`
- Data minimization enforcement
- Automated retention and cleanup with `DataRetentionService`

### Reporting & Outreach
- Scheduled email reports (`MonthlyComplianceReport`, `WeeklyProgressReport`)
- QR code batch generation and statistics (`QrCodeService`)
- Multi-format exports (PDF, Excel)
- Goal tracking and metrics

### User Experience
- Dedicated student authentication flow
- Email verification system
- Admin dashboards with real-time updates
- Session diagnostics
- Legacy HTML prototypes for communication aides

---

## Technology Stack

### Backend
- **Framework**: Laravel 12, PHP 8.3
- **Authentication**: Sanctum
- **Architecture**: Service-layer pattern under `app/Services`
- **PDF Generation**: DomPDF
- **Excel Export**: Laravel Excel
- **Email**: Resend mail driver

### Frontend
- **Templates**: Blade views in `resources/views`
- **Styling**: Tailwind CSS 4 compiled by Vite 7
- **JavaScript**: Axios-backed dashboards
- **Components**: Reusable components in `resources/js`

### Data & Privacy
- **Database**: SQLite (default), MySQL, PostgreSQL support
- **Encryption**: AES-256 via `EncryptionService`
- **Privacy**: Anonymization hashing, consent tracking, retention policies

### Automation & Integrations
- **AI Service**: Flask AI service with circuit breaker (default `FLASK_AI_SERVICE_URL`)
- **Fallback**: PHP-ML for local processing
- **Queue System**: Laravel queue-ready jobs
- **Scheduling**: Cron-friendly artisan commands

---

## Architecture

### Directory Structure

```
Project-ISO/
├── app/
│   ├── Http/Controllers/     # Entry points for web and API routes
│   ├── Services/             # Business logic and integrations
│   ├── Console/Commands/     # Scheduled tasks and automation
│   ├── Mail/                 # Email templates and notifications
│   └── Models/               # Eloquent models
├── database/
│   ├── migrations/           # Database schema evolution
│   └── seeders/              # Default data (AdminSeeder)
├── resources/
│   ├── views/                # Blade templates (admin, student, survey)
│   └── js/                   # Frontend JavaScript
├── routes/
│   ├── web.php               # Web routes
│   └── api.php               # API routes
├── config/                   # Configuration files
├── docs/                     # Implementation guides and runbooks
└── public/                   # Compiled assets and QR downloads
```

### Key Components

- **Controllers**: `app/Http/Controllers` with aligned `routes/web.php` and `routes/api.php` define student, admin, analytics, AI, and export entry points
- **Services**: `app/Services` encapsulate analytics, AI integration, caching, consent, data minimization, retention, encryption, and visualization logic
- **Automation**: `app/Console/Commands`, `app/Mail`, and `app/Notifications` automate weekly aggregation, reporting, diagnostics, and compliance messaging
- **Database**: `database/migrations` and `database/seeders` maintain ISO 21001 schema evolution and ship default admin accounts
- **UI**: `resources/views` and the Vite/Tailwind pipeline deliver the interface; `public/` hosts compiled assets and QR downloads
- **Documentation**: `docs/` holds implementation guides and runbooks, while `html/` preserves earlier static prototypes for reference

---

## Installation & Setup

### Prerequisites

- PHP 8.3+ with Composer
- Database engine (SQLite default; MySQL/PostgreSQL supported)
- Node.js 18+ with npm for asset compilation
- Python 3.10+ (optional, for Flask AI microservice)
- Redis (optional, for cache/queue backends)

### Quick Start

1. **Clone the repository and install dependencies:**
   ```bash
   git clone <repository-url>
   cd Project-ISO
   composer install
   npm install
   ```

2. **Prepare environment variables and application key:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure your database connection in `.env` and run migrations:**
   ```bash
   php artisan migrate --seed
   ```
   > **Note**: The `AdminSeeder` provisions default admin account: `kwadrateam@gmail.com / Admin@1`

4. **Compile or watch assets:**
   ```bash
   npm run build      # production bundle
   npm run dev        # Vite dev server with hot reload
   ```

5. **Launch the application:**
   ```bash
   php artisan serve
   ```
   > **Tip**: For an end-to-end local development loop, run `composer dev` to start PHP, queue listener, Laravel Pail, and Vite concurrently.

6. **Optional: Configure Flask AI service:**
   Set `FLASK_AI_SERVICE_URL`, `FLASK_AI_API_KEY`, and related toggles in `.env` to connect to the remote or local Flask AI service.

For deeper setup guidance, see [`docs/LOCAL_DEVELOPMENT_GUIDE.md`](docs/LOCAL_DEVELOPMENT_GUIDE.md).

---

## Configuration

### Environment Variables

Key environment variables to configure in `.env`:

#### AI Service
- `FLASK_AI_SERVICE_URL` - Flask AI service endpoint
- `FLASK_AI_API_KEY` - API key for authentication
- `AI_*` - Additional AI configuration options
- Configuration file: `config/ai.php`

#### Email Delivery
- `RESEND_API_KEY` - Required for scheduled reports (`MonthlyComplianceReport`, `WeeklyProgressReport`)
- Mail driver configuration in `config/mail.php`

#### Caching & Queues
- `CACHE_DRIVER` - Cache backend (file, redis, etc.)
- `SESSION_DRIVER` - Session storage driver
- `QUEUE_CONNECTION` - Queue backend (sync, redis, database, etc.)
- `CacheService` centralizes tagged cache usage

#### Privacy Controls
- Configuration file: `config/privacy.php`
- Services enforce encryption, anonymization, consent logging, and retention
- See [`docs/DATA_PRIVACY_IMPLEMENTATION.md`](docs/DATA_PRIVACY_IMPLEMENTATION.md) for details

#### Exports & Storage
- `FILESYSTEM_DISK` - Storage disk for report delivery and QR storage
- DomPDF and Laravel Excel drive export formats

### Service Configuration

- **AI Service**: Configure endpoints and circuit breaker options in `.env` and `config/ai.php`
- **Email**: Set `RESEND_API_KEY` for scheduled reports
- **Privacy**: Review `config/privacy.php` for encryption, anonymization, and retention settings
- **Storage**: Configure `FILESYSTEM_DISK` for file storage

---

## Usage & Operations

### Scheduled Commands

The following artisan commands are scheduler-ready and should be added to your cron:

- `app:aggregate-weekly-metrics` - Aggregates weekly metric data
- `app:send-weekly-progress-reports` - Sends weekly progress reports via email
- `app:generate-monthly-reports` - Generates monthly compliance reports
- `data:retention-cleanup` - Enforces data retention policies

**Example cron configuration:**
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Admin Features

- **QR Code Management**: `QrCodeController` supports batch generation, exports, download, and statistics for survey distribution
- **Indirect Metrics**: `IndirectMetricsController` ingests CSV uploads and manual updates for non-survey metrics
- **Debug Routes**: `/debug-login`, `/debug-auth` surface session diagnostics in non-production environments

### Queue Workers

Keep analytics responsive by running queue workers:

```bash
php artisan queue:work
```

For cache warming:

```bash
php artisan cache:warmup
```

---

## Development

### Testing

Run the full test suite:

```bash
php artisan test
```

Filter by class or method for focused scenarios:

```bash
php artisan test --filter SurveyResponseTest
php artisan test tests/Feature/AnalyticsIntegrationTest.php
```

**Test Structure:**
- Feature tests: `tests/Feature/` - Includes ISO 21001 compliance checks
- Unit tests: `tests/Unit/` - Service and model tests

### Code Quality

Enforce coding standards with Laravel Pint:

```bash
./vendor/bin/pint
```

Verify production assets:

```bash
npm run build
# or
vite build --mode production
```

---

## Documentation

Comprehensive documentation is available in the `docs/` directory:

### Core Documentation
- [`docs/iso-21001-system-documentation.md`](docs/iso-21001-system-documentation.md) - End-to-end system blueprint and compliance mapping
- [`docs/DATA_PRIVACY_IMPLEMENTATION.md`](docs/DATA_PRIVACY_IMPLEMENTATION.md) - Encryption, anonymization, consent, and retention playbook
- [`docs/AUDIT_COMPLIANCE_IMPLEMENTATION.md`](docs/AUDIT_COMPLIANCE_IMPLEMENTATION.md) - Audit logging, compliance dashboards, and reporting workflows

### User Guides
- [`docs/LOCAL_DEVELOPMENT_GUIDE.md`](docs/LOCAL_DEVELOPMENT_GUIDE.md) - Environment provisioning, tooling, and troubleshooting
- [`docs/USER_ADMIN_MANUAL.md`](docs/USER_ADMIN_MANUAL.md) - Student and admin journey walkthroughs
- [`docs/qr-code-documentation.md`](docs/qr-code-documentation.md) - QR generation, scanning analytics, and remediation tips

### Deployment Guides
- [`docs/DEPLOYMENT_README.md`](docs/DEPLOYMENT_README.md) - General deployment guidelines
- [`docs/cloudways-deployment.md`](docs/cloudways-deployment.md) - Cloudways-specific deployment
- [`docs/fly-deployment.md`](docs/fly-deployment.md) - Fly.io deployment guide

### Change Log
- [`Revisions.md`](Revisions.md) - Internal change logs and revision history

---

## Support

### Contact Information
- **Primary Contact**: support@kwadrateam.dev

### Additional Resources
- Internal documentation and change logs: `docs/` directory and `Revisions.md`
- Issue tracking and bug reports: Please contact the development team via email

---

**Last Updated**: 2025-11-07
