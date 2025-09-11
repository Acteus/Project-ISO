# Frontend Documentation - ISO 21001 Student Survey System

## Overview

This document provides guidance for the frontend aspects of the ISO 21001 Student Survey System, a Laravel-based backend application with minimal frontend components. The system focuses on survey management, AI compliance analysis, data visualization, and exports, primarily served through backend-rendered Blade views.

## Architecture Overview

The application is primarily backend-driven with basic frontend assets compiled via Vite. There are no complex JavaScript frameworks or single-page application features; the UI consists of simple Blade templates for admin dashboards and forms.

### Technology Stack

- **Build Tool**: Vite (for asset compilation)
- **CSS**: Basic TailwindCSS setup (minimal usage)
- **JavaScript**: Vanilla JS with Axios for API calls (if needed)
- **Templates**: Laravel Blade for server-rendered views
- **Backend Integration**: Laravel Sanctum for API authentication

### Current State

The frontend assets are minimal:
- Vite configuration for building CSS/JS
- Basic TailwindCSS imports (not heavily used)
- Axios configured for potential API interactions
- No complex components or routing

## Project Structure

```
resources/
├── js/
│   ├── app.js          # Main entry point (imports bootstrap)
│   ├── bootstrap.js    # Axios setup
│   └── app.js          # Basic JS (no advanced features)
└── css/
    └── app.css         # TailwindCSS imports and basic styles
```

### Key Files

#### `resources/js/app.js`
```javascript
import './bootstrap';
```
- Simple entry point
- Imports bootstrap.js
- No additional modules currently

#### `resources/js/bootstrap.js`
```javascript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```
- Basic Axios configuration
- Available globally for API calls from JS
- CSRF headers for Laravel compatibility

#### `resources/css/app.css`
```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
}
```
- Basic TailwindCSS import
- Source paths for purging unused styles
- Custom font configuration (minimal usage in views)

## Development Setup

### Prerequisites

- Node.js (v18+ recommended)
- npm or yarn
- PHP 8.2+ (for Laravel 11)
- Composer
- MySQL or compatible database

### Installation

1. **Install PHP dependencies:**
   ```bash
   composer install
   ```

2. **Install Node dependencies:**
   ```bash
   npm install
   ```

3. **Copy environment file and generate key:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations and seed:**
   ```bash
   php artisan migrate --seed
   ```

5. **Build assets:**
   ```bash
   npm run build
   ```

### Development Workflow

1. **Start the Laravel server:**
   ```bash
   php artisan serve
   ```

2. **Build assets (for production) or run dev server:**
   ```bash
   npm run dev  # Development with HMR
   # or
   npm run build  # Production build
   ```

3. **Access the application:**
   - Application at `http://localhost:8000`
   - Assets served from Laravel public directory

## Backend Integration

The application uses server-rendered Blade views for the UI. API endpoints are available for programmatic access or potential AJAX calls, protected by Laravel Sanctum.

### Authentication Flow

1. **Admin Login (API):**
   ```javascript
   const response = await axios.post('/api/admin/login', {
       email: 'admin@example.com',
       password: 'password'
   });
   const token = response.data.token;
   localStorage.setItem('auth_token', token);
   ```

2. **Set Authorization Header:**
   ```javascript
   const token = localStorage.getItem('auth_token');
   axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
   ```

3. **Logout (API):**
   ```javascript
   await axios.post('/api/admin/logout');
   localStorage.removeItem('auth_token');
   ```

### Key API Endpoints

#### Authentication
- `POST /api/admin/login` - Admin login
- `POST /api/admin/logout` - Admin logout
- `GET /api/admin/me` - Get current admin profile

#### Survey Management
- `POST /api/survey/submit` - Submit survey response
- `GET /api/survey/responses` - Get survey responses
- `GET /api/survey/analytics` - Get survey analytics

#### AI Features (ISO 21001 Compliance)
- `POST /api/ai/compliance-check` - Check ISO 21001 compliance
- `POST /api/ai/generate-report` - Generate compliance report
- `GET /api/ai/insights` - Get AI-generated insights

#### Data Visualization
- `GET /api/visualizations/survey-data` - Survey data for charts
- `GET /api/visualizations/compliance-metrics` - Compliance visualization data
- `GET /api/visualizations/trends` - Survey trends over time

#### Exports
- `GET /api/export/survey-responses` - Export survey responses (Excel/CSV)
- `GET /api/export/compliance-report` - Export compliance report (PDF/Excel)

## Frontend Development Guidelines

### Basic JavaScript Usage

For any AJAX calls in Blade views:

1. **Simple API Call:**
   ```javascript
   const fetchData = async () => {
       try {
           const response = await window.axios.get('/api/survey/responses');
           // Handle response
       } catch (error) {
           console.error('Error:', error);
       }
   };
   ```

2. **Error Handling:**
   ```javascript
   window.axios.interceptors.response.use(
       response => response,
       error => {
           if (error.response.status === 401) {
               window.location.href = '/login';
           }
           return Promise.reject(error);
       }
   );
   ```

### Styling Guidelines

1. **Blade View Styling:**
   Use Tailwind classes directly in Blade templates:
   ```blade
   <div class="container mx-auto p-6">
       <h1 class="text-2xl font-bold mb-4">Survey Dashboard</h1>
       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
           <!-- content -->
       </div>
   </div>
   ```

2. **Custom CSS:**
   Add to `resources/css/app.css` if needed, but prefer Tailwind utilities.

### Blade Views Structure

Current views are minimal. Key locations:
```
resources/views/
├── welcome.blade.php     # Landing page
├── admin/                # Admin dashboard views (to be created)
├── surveys/              # Survey forms and lists
└── layouts/              # Base layouts (app.blade.php)
```

## Security Considerations

1. **Never store sensitive data in localStorage:**
   - Use secure storage for tokens
   - Implement token refresh logic

2. **Validate user input:**
   - Client-side validation for UX
   - Server-side validation for security

3. **Handle authentication errors:**
   - Redirect to login on 401 responses
   - Clear tokens on logout

## Performance Optimization

1. **Code splitting:**
   ```javascript
   // Dynamic imports for large components
   const AnalyticsDashboard = () => import('./components/AnalyticsDashboard');
   ```

2. **Lazy loading:**
   ```javascript
   // Load components only when needed
   const loadChart = async () => {
       const { Chart } = await import('chart.js');
       // use Chart
   };
   ```

3. **Optimize TailwindCSS:**
   - Only include used classes in production
   - Use `@source` directives to specify file paths

## Testing

### Frontend Testing

Minimal JS means limited frontend testing. Focus on:

1. **Browser Testing:** Manual verification of Blade views and forms.

2. **API Testing:** Use tools like Postman to test endpoints.

For backend tests, see `tests/Feature/` (e.g., ISO21001ComplianceTest.php).

## Deployment

1. **Set up production environment:**
   ```bash
   cp .env.example .env
   # Configure .env (DB, APP_KEY, etc.)
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Build assets:**
   ```bash
   npm run build
   ```

3. **Run migrations:**
   ```bash
   php artisan migrate --force
   ```

4. **Assets served from:** `public/build/` directory.

## Future Development

### Current Features

The system supports:
- Admin authentication
- Survey response submission and management
- ISO 21001 compliance analysis via AI
- Data visualization (charts, metrics)
- Exports (Excel, CSV for survey responses)
- Audit logging for actions

For UI enhancements, extend Blade views or integrate JS libraries as needed.

## Troubleshooting

### Common Issues

1. **Assets not loading:**
   - Run `npm run build` for production
   - Check `vite.config.js` paths
   - Clear browser cache

2. **Authentication errors:**
   - Verify Sanctum middleware in routes/api.php
   - Check .env for SESSION_DOMAIN if needed
   - Ensure `php artisan sanctum:publish` if customizing

3. **Database/Backend issues:**
   - Run `php artisan migrate`
   - Check `storage/logs/laravel.log`
   - Verify .env database config

### Debug Tips

- Browser dev tools for views/styling
- Laravel logs: `tail -f storage/logs/laravel.log`
- API testing: Postman or `php artisan tinker`
- Database: `php artisan db:show` or phpMyAdmin

## Resources

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Vite Documentation](https://vitejs.dev/)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)
- [ISO 21001 Standard](https://www.iso.org/standard/63087.html) (for compliance context)

## Contributing

1. Follow Laravel coding standards
2. Use descriptive commit messages
3. Run tests: `php artisan test`
4. Update docs for new views/endpoints
5. Submit PRs with changes

---

Documentation reflects the current backend-focused application state as of September 2025.
