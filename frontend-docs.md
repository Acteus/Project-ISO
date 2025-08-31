# Frontend Documentation - Student Survey System

## Overview

This document provides comprehensive guidance for frontend developers working on the Student Survey System. The system is built using Laravel's asset compilation system with modern frontend tools for optimal development experience.

## Architecture Overview

The frontend is integrated within the Laravel backend repository and uses Laravel's asset compilation system. The current setup is minimal and serves as a foundation for building the complete frontend application.

### Technology Stack

- **Build Tool**: Vite (fast, modern bundler)
- **CSS Framework**: TailwindCSS v4 (utility-first CSS framework)
- **JavaScript**: ES6+ modules
- **HTTP Client**: Axios (for API communication)
- **Backend Integration**: Laravel Sanctum (token-based authentication)

### Current State

The frontend is currently in its initial setup phase with:
- Basic Vite configuration
- TailwindCSS integration
- Axios setup for API calls
- Minimal JavaScript structure

## Project Structure

```
resources/
├── js/
│   ├── app.js          # Main JavaScript entry point
│   └── bootstrap.js    # Axios configuration and global setup
└── css/
    └── app.css         # Main CSS entry point with TailwindCSS
```

### Key Files

#### `resources/js/app.js`
```javascript
import './bootstrap';
```
- Main entry point for JavaScript
- Currently only imports the bootstrap file
- This is where you would import additional JavaScript modules

#### `resources/js/bootstrap.js`
```javascript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```
- Configures Axios globally
- Sets up CSRF token headers for Laravel
- Makes Axios available as `window.axios`

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
- Imports TailwindCSS
- Configures source paths for TailwindCSS purging
- Sets custom font stack

## Development Setup

### Prerequisites

- Node.js (v16 or higher)
- npm or yarn
- PHP 8.1+ (for Laravel backend)
- Composer

### Installation

1. **Install Node dependencies:**
   ```bash
   npm install
   ```

2. **Start development server:**
   ```bash
   npm run dev
   ```
   This will start Vite's development server with hot module replacement.

3. **Build for production:**
   ```bash
   npm run build
   ```
   This compiles and minifies assets for production.

### Development Workflow

1. **Start the Laravel backend:**
   ```bash
   php artisan serve
   ```

2. **Start the frontend development server:**
   ```bash
   npm run dev
   ```

3. **Access the application:**
   - Frontend assets will be served via Vite at `http://localhost:5173` (or configured port)
   - Laravel backend at `http://localhost:8000`

## Backend Integration

The frontend communicates with the Laravel backend through RESTful APIs. All API endpoints require authentication using Laravel Sanctum tokens.

### Authentication Flow

1. **Login:**
   ```javascript
   const response = await axios.post('/api/admin/login', {
       email: 'admin@example.com',
       password: 'password'
   });
   const token = response.data.token;
   // Store token in localStorage or secure storage
   localStorage.setItem('auth_token', token);
   ```

2. **Authenticated Requests:**
   ```javascript
   const token = localStorage.getItem('auth_token');
   axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
   ```

3. **Logout:**
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
- `GET /api/survey/analytics` - Get survey analytics

#### AI Features
- `POST /api/ai/compliance-predict` - Compliance prediction
- `GET /api/ai/cluster-responses` - Cluster analysis
- `GET /api/ai/sentiment-analysis` - Sentiment analysis

#### Data Visualization
- `GET /api/visualizations/bar-chart` - Bar chart data
- `GET /api/visualizations/pie-chart` - Pie chart data
- `GET /api/visualizations/word-cloud` - Word cloud data

#### Export
- `GET /api/export/excel` - Export to Excel
- `GET /api/export/csv` - Export to CSV
- `GET /api/export/pdf` - Export to PDF

## Frontend Development Guidelines

### JavaScript Best Practices

1. **Use ES6+ features:**
   ```javascript
   // Use arrow functions
   const fetchData = async () => {
       try {
           const response = await axios.get('/api/data');
           return response.data;
       } catch (error) {
           console.error('Error fetching data:', error);
       }
   };
   ```

2. **Organize code into modules:**
   ```javascript
   // auth.js
   export const login = async (credentials) => {
       // login logic
   };

   export const logout = async () => {
       // logout logic
   };

   // app.js
   import { login, logout } from './auth';
   ```

3. **Handle errors gracefully:**
   ```javascript
   try {
       const result = await apiCall();
       // handle success
   } catch (error) {
       if (error.response?.status === 401) {
           // redirect to login
       } else {
           // show error message
       }
   }
   ```

### CSS/Styling Guidelines

1. **Use TailwindCSS utility classes:**
   ```html
   <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
       <!-- content -->
   </div>
   ```

2. **Custom CSS when needed:**
   ```css
   /* In app.css or separate component files */
   .custom-button {
       @apply bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded;
   }
   ```

3. **Responsive design:**
   ```html
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
       <!-- responsive grid -->
   </div>
   ```

### Component Structure

As the frontend grows, organize components logically:

```
resources/js/
├── components/
│   ├── auth/
│   │   ├── LoginForm.js
│   │   └── LogoutButton.js
│   ├── survey/
│   │   ├── SurveyForm.js
│   │   └── SurveyList.js
│   └── dashboard/
│       ├── AnalyticsChart.js
│       └── ExportButton.js
├── services/
│   ├── api.js
│   └── auth.js
├── utils/
│   └── helpers.js
└── app.js
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

### Unit Testing
```javascript
// Example test with Jest
import { login } from './auth';

test('login function works correctly', async () => {
    const credentials = { email: 'test@example.com', password: 'password' };
    const result = await login(credentials);
    expect(result.token).toBeDefined();
});
```

### Integration Testing
- Test API integrations
- Test authentication flows
- Test form submissions

## Deployment

1. **Build assets:**
   ```bash
   npm run build
   ```

2. **Laravel Mix will compile assets automatically**
   - Assets are compiled to `public/build/`
   - Laravel serves compiled assets

3. **Environment-specific builds:**
   - Development: `npm run dev`
   - Production: `npm run build`

## Future Development

### Planned Features

1. **React Integration:**
   - Migrate to React for better component management
   - Implement state management (Redux/Zustand)
   - Add routing (React Router)

2. **Enhanced UI Components:**
   - Survey form builder
   - Interactive charts (Chart.js/D3.js)
   - Admin dashboard
   - Data visualization components

3. **Progressive Web App (PWA):**
   - Offline functionality
   - Push notifications
   - App-like experience

### Migration Path

1. **Phase 1:** Enhance current setup with more components
2. **Phase 2:** Integrate React incrementally
3. **Phase 3:** Full React migration with modern tooling

## Troubleshooting

### Common Issues

1. **Assets not loading:**
   - Ensure Vite dev server is running
   - Check browser console for errors
   - Verify file paths in `vite.config.js`

2. **API authentication errors:**
   - Check if Sanctum token is set correctly
   - Verify CSRF token configuration
   - Check network tab for request details

3. **Styling issues:**
   - Clear browser cache
   - Rebuild assets: `npm run build`
   - Check TailwindCSS configuration

### Debug Tips

- Use browser developer tools
- Check Laravel logs: `storage/logs/laravel.log`
- Use `console.log()` for debugging
- Test API endpoints with tools like Postman

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vite Documentation](https://vitejs.dev/)
- [TailwindCSS Documentation](https://tailwindcss.com/)
- [Axios Documentation](https://axios-http.com/)
- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)

## Contributing

1. Follow the established coding standards
2. Write clear, concise commit messages
3. Test your changes thoroughly
4. Update documentation as needed
5. Create pull requests for review

---

This documentation will be updated as the frontend evolves. Please check back regularly for the latest information.
