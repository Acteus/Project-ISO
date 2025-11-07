<!--
  Combined User + Admin Manual for Project-ISO
  Generated automatically by assistant after scanning repository.
  Location: docs/USER_ADMIN_MANUAL.md
-->
# Project-ISO — User & Admin Manual

This single-file manual provides end-user (student) and administrator (admin) instructions for the Project-ISO application (Jose Rizal University ISO 21001 Student Survey System). It covers installation, daily usage, administration, APIs, deployment, troubleshooting, and security guidance.

> Note: This manual was produced from the repository contents. If your environment differs (custom .env, database, or hosting), follow local conventions and treat environment variables as the source of truth.

---

## Quick reference

- Project root: repository `Project-ISO`
- Main web entry: `/` (welcome), Student dashboard at `/student/dashboard`, Admin dashboard at `/admin/dashboard`
- Survey path: `/survey` (form), public landing `/home`
- Admin QR public URLs: `/qr/{id}`
- API root: `/api/*` (see API Summary section)

Files and docs referenced while creating this manual:

- `README.md` (project overview & installation)
- `docs/iso-21001-system-documentation.md`, `docs/stem-data-model.md`, `docs/qr-code-documentation.md`
- Routes: `routes/web.php`, `routes/api.php`
- Key controllers: `app/Http/Controllers/SurveyController.php`, `QrCodeController.php`, `AdminAuthController.php`
- Key models: `app/Models/SurveyResponse.php`, `QrCode.php`, `User.php`

---

## 1 — Overview & goals

Project-ISO is a Laravel-based survey and analytics platform tailored to ISO 21001 educational quality assessment. It collects student survey responses, computes ISO indices, provides dashboards, integrates an AI analytics service (optional), supports QR code-based distribution, and includes admin tools for reporting, exports and goals.

This manual is split into two main sections: "Student (End User) Guide" and "Administrator Guide". Read the relevant sections first. The API summary and deployment sections are for both developers and admins.

---

## 2 — Installation & initial setup (for admins / devops)

Minimum requirements (from `composer.json` / repo):

- PHP >= 8.2, Composer
- Laravel 12
- Node.js 18+ and npm
- Database: SQLite (dev), MySQL or PostgreSQL (recommended for production)
- Optional: Python 3.8+ for the Flask AI service; Redis for caching

Step-by-step (development-friendly):

1. Clone the repo and enter it:

   git clone <repo-url>
   cd Project-ISO

2. Install dependencies:

   composer install --optimize-autoloader --no-dev
   npm install

3. Setup environment and key:

   cp .env.example .env
   php artisan key:generate

4. Configure DB in `.env` (sqlite example):

   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite

   # Or set MySQL/Postgres details accordingly

5. Create DB file (if using sqlite) and run migrations & seeds:

   php artisan migrate
   php artisan db:seed --class=AdminSeeder

6. Build assets (dev or prod):

   npm run dev    # development (watch)
   npm run build  # production build

7. Start server:

   php artisan serve --host=0.0.0.0 --port=8000

8. (Optional) Start / configure Flask AI service and set `FLASK_AI_SERVICE_URL` in `.env`.

Maintenance commands (useful to automate):

- `php artisan weekly:aggregate` — aggregate weekly metrics
- `php artisan reports:send-weekly` — send weekly report
- `php artisan reports:generate-monthly` — generate monthly compliance reports

See `README.md` for details and additional deployment guides in `DEPLOYMENT_README.md`.

---

## 3 — Student (End User) Guide

This section describes how a student uses the system to register, authenticate, and submit surveys.

### 3.1 Registration

- Open `https://<your-host>/student/register` or click "Register" on landing page.
- Required fields: name, email, password, student identifier (if requested). The system may require email verification.
- After registration, follow the verification email link.

### 3.2 Login

- Login at `/student/login`.
- If you forget your password, use the Password Reset flow (`/password/forgot`).

### 3.3 Taking the Survey

1. Go to the survey landing page `/home` or scan the QR code provided for your class.
2. If not logged in, you will be prompted to register/login. Anonymous submission is supported via generated anonymous IDs (the system falls back to an anonymous ID when no student_id is provided).
3. Complete the ISO 21001 survey form (`/survey`), which uses a 1–5 Likert scale across multiple sections (Learner Needs, Satisfaction, Success, Safety, Wellbeing) and accepts qualitative feedback.
4. Consent is required: check consent box before submission (field `consent_given`).
5. After successful submission you will see a thank-you page `/thank-you`.

Notes on privacy: sensitive free-text fields and `student_id` are encrypted; the platform hides student-identifying fields in API responses and exports by default.

### 3.4 Student Dashboard

- Students can view personal submission status and related resources at `/student/dashboard`.

### 3.5 Troubleshooting (student)

- Unable to register or login: confirm email verification; check spam for verification emails.
- Form validation errors: the server returns field-level messages — correct the highlighted fields and resubmit.
- QR redirect doesn't work: contact admin; the QR could be expired or misconfigured.

---

## 4 — Administrator Guide

This section covers admin responsibilities: login, dashboards, QR management, goals, analytics, exports, and operational upkeep.

### 4.1 Admin Authentication

- API login endpoint: `POST /api/admin/login` (returns Sanctum token)
- Web session login handled by admin controller (see `AdminAuthController`).
- Use role-based separation: admin vs student roles are enforced.

### 4.2 Admin Dashboard & Key Views

- Admin Dashboard: `/admin/dashboard` — high-level analytics and links to management areas.
- AI Insights: `/admin/ai-insights` — ML-driven predictions and risk meters (requires Flask AI service or falls back to PHP-ML).
- Responses: `/admin/responses` — view paginated survey responses (sensitive fields hidden).
- Audit logs: `/admin/audit-logs` — traceable activity log for compliance.

### 4.3 Managing QR Codes

QR Codes allow students to scan and directly land on survey pages.

Where: Admin → QR Code Management (`/admin/qr-codes`).

Operations:

- Create single QR: provide `name`, `target_url`, `track` (CSS), `grade_level`, `section`, `format` (png/svg), `size`, colors, `academic_year`, `version`, optional `expires_at`.
- Batch create: create QR codes for many sections at once via `/admin/qr-codes/batch-generate`.
- Download/Print: admin may download QR files for classroom posters.
- View stats: QR scan counts and limited analytics available via controller endpoints and `qrCodeService`.

Best practices:

- Set expiration on campaign-based QR codes.
- Use distinct `version` values to track survey cycles.
- Monitor scan counts and adjust distribution if scan rates are low.

### 4.4 Goals and Weekly Progress

- Goals are managed at `/admin/goals` (resource controller). A goal ties a target metric, target date, and progress history.
- Weekly metrics are aggregated by `php artisan weekly:aggregate` (scheduler recommended). Weekly aggregation populates `weekly_metrics` and powers trend visualizations.

### 4.5 Analytics & Reports

- The app calculates ISO indices (Learner Needs, Satisfaction, Success, Safety, Wellbeing) and correlation metrics from `SurveyResponse`.
- Admins can export analytics to Excel/CSV/PDF via `/api/export/*` endpoints or via UI ExportController.
- Exports and reports include consent checks — only export data consistent with privacy settings.

### 4.6 AI Integration

- Optional Flask AI microservice provides additional ML models (compliance prediction, sentiment analysis, clustering, forecasting).
- Configure `FLASK_AI_SERVICE_URL` in `.env` to point to the AI service.
- The system uses PHP-ML as a fallback when the Flask service is unavailable.

### 4.7 Audit Logging & Compliance

- All critical admin actions (create/update/delete QR codes, export data, view analytics) are logged to `AuditLog` for ISO traceability.
- Ensure `AuditLog` database retention meets organizational policy.

### 4.8 Admin troubleshooting

- Missing QR files: confirm storage is writable and `storage:link` has been run (if public storage used).
- Export failures: confirm `maatwebsite/excel` and `dompdf` packages are installed and configured.
- AI service errors: check `FLASK_AI_SERVICE_URL`, network access, and the AI service health endpoint.

---

## 5 — API Summary (common / key endpoints)

Authentication:

- POST `/api/admin/login` — login, returns token
- POST `/api/admin/logout` — logout (auth required)
- GET `/api/admin/me` — get current admin (auth required)

Survey management:

- POST `/api/survey/submit` — submit survey response (accepts JSON; consent required)
- GET `/api/survey/analytics` — get analytics (admin-protected on API routes)
- GET `/api/survey/responses` — paginated listing (admin)

Analytics (simplified v2):

- GET `/api/analytics/summary` — summary metrics
- GET `/api/analytics/time-series` — time series for metrics
- GET `/api/analytics/compliance` — compliance data

AI endpoints (session/auth protected):

- POST `/api/ai/predict-compliance` — compliance prediction
- POST `/api/ai/cluster-responses` — clustering
- POST `/api/ai/analyze-sentiment` — sentiment analysis

QR & Export:

- Resourceful QR web routes: `/admin/qr-codes` (index, create, store, show, edit, update, destroy)
- POST `/admin/qr-codes/batch-generate` — batch create
- GET `/admin/qr-codes/{id}/download` — download QR image
- GET `/api/export/excel` | `/api/export/csv` | `/api/export/pdf` — export reports

Notes: API routes use Sanctum token-based auth for protected endpoints. Public survey submission endpoint deliberately uses `web` middleware (session) to support non-token flows.

---

## 6 — Data model & privacy highlights

- SurveyResponse model contains the ISO indices and fields. Sensitive fields (student_id, text responses) are encrypted at rest using Laravel's encryption (see `SurveyResponse` model mutators/accessors).
- The system hides sensitive fields when returning API responses by default. Admin exports may include more data depending on config and consent.

Fields of note:

- `student_id` (encrypted), `anonymous_id` (hash for analytics), `consent_given` (boolean required), Likert fields (1–5 scale), and indirect metrics (attendance, grade average).

Retention & security:

- Follow data retention policies. The app logs audit entries for compliance and stores only what is needed for ISO reporting and analytics.

---

## 7 — Deployment checklist (production)

1. Set `.env` with production DB credentials, `APP_ENV=production`, and a secure `APP_KEY`.
2. Install composer dependencies and run `php artisan migrate --force`.
3. Build assets with `npm run build`.
4. Run `php artisan config:cache`, `route:cache`, `view:cache`.
5. Configure webserver to serve `public/` directory and set proper file permissions (storage, bootstrap/cache).
6. Setup supervisor/queue worker and schedule (cron): `* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1`.
7. Configure backups for DB and storage.
8. Optionally deployment pipelines: Docker, Forge, Cloudways; refer to `DEPLOYMENT_README.md`.

---

## 8 — Troubleshooting & logs

- Logs: check `storage/logs/laravel.log` for runtime errors and stack traces.
- Debug routes (only in dev) exist: `/debug-login`, `/debug-auth` — these output session/auth info and are helpful for diagnosing session issues.
- Common issues:
  - Permissions: ensure `storage/` and `bootstrap/cache` are writable by the web user.
  - Missing env vars: missing `APP_KEY`, `DB_*` or `FLASK_AI_SERVICE_URL` produce runtime errors — confirm `.env` values.
  - Queue workers: ensure `queue:work` or a supervisor-managed worker is running for background jobs.

---

## 9 — Backups & retention

- Implement regular database dumps and storage backup for uploaded QR code images and generated reports.
- Audit logs should be exported and archived according to institutional compliance policy.

---

## 10 — Security & compliance notes

- Encryption: sensitive text fields and student identifiers are encrypted using Laravel's Crypt service.
- Authorization: API endpoints use Sanctum; web routes have role checks.
- Audit trail: `AuditLog` stores who did what with timestamps and IP addresses.
- GDPR/Privacy: consent flag is mandatory for survey submission. Use anonymized exports when possible.

---

## 11 — Tests & code quality

- Run test suite: `php artisan test` (see `phpunit.xml`).
- Code style: `./vendor/bin/pint` (Laravel Pint) for formatting.

---

## 12 — Contacts & support

For local support, contact your university IT team or the development team listed in `README.md`.

---

## 13 — Next steps & optional improvements

- Add an Admin FAQ and step-by-step screenshots in `docs/` for non-technical admin users.
- Expand API documentation into an OpenAPI/Swagger file.
- Add monitoring/health checks (Prometheus/Grafana) for performance and AI service health.

---

If you'd like, I can:

- Expand any section with step-by-step screenshots or CLI scripts.
- Generate a separate quickstart README for non-technical admins.
- Produce a Swagger/OpenAPI spec for the API endpoints.

Tell me which follow-up you'd like and I'll implement it.
