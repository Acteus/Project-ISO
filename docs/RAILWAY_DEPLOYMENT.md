# Railway Deployment Guide for ISO 21001 Survey System

This guide provides step-by-step instructions for deploying the Jose Rizal University ISO 21001 Student Survey System to Railway, including both the Laravel application and the Flask AI service.

## Overview

The system consists of two main components:
1. **Laravel Application**: Main web application with survey forms, admin dashboard, and analytics
2. **Flask AI Service**: Python microservice providing 8 machine learning models for compliance prediction and analytics

## Prerequisites

- Railway account with active subscription
- Git repository with the project code
- Basic understanding of Railway's platform

## Railway Project Structure

We'll deploy both services as separate Railway services within the same project:

```
iso-21001-survey-system (Railway Project)
├── laravel-app (Laravel Service)
└── ai-service (Flask Service)
```

## 1. Laravel Application Deployment

### Railway Configuration

Create a new Railway project and add the Laravel service:

#### Environment Variables (Laravel Service)

Set the following environment variables in Railway:

```env
# Application Configuration
APP_NAME="Jose Rizal University - ISO 21001 Survey System"
APP_ENV=production
APP_KEY=base64:your-generated-app-key
APP_DEBUG=false
APP_URL=https://your-railway-app-url.up.railway.app

# Database Configuration (Railway PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=${{PGHOST}}
DB_PORT=${{PGPORT}}
DB_DATABASE=${{PGDATABASE}}
DB_USERNAME=${{PGUSER}}
DB_PASSWORD=${{PGPASSWORD}}

# Cache & Session Configuration
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# AI Service Configuration
FLASK_AI_SERVICE_URL=https://your-ai-service-url.up.railway.app
FLASK_AI_API_KEY=
AI_TIMEOUT_SECONDS=30
AI_MAX_RETRIES=3
AI_ENABLE_CACHE=true
AI_CACHE_TTL=300
AI_FALLBACK_TO_PHP=true
AI_SERVICE_CHECK_INTERVAL=60

# AI Model Configuration
AI_COMPLIANCE_MODEL_ENABLED=true
AI_COMPLIANCE_MIN_CONFIDENCE=0.5
AI_SENTIMENT_MODEL_ENABLED=true
AI_SENTIMENT_MIN_CONFIDENCE=0.6
AI_CLUSTER_MODEL_ENABLED=true
AI_DEFAULT_CLUSTERS=3
AI_MAX_CLUSTERS=8

# Performance Settings
AI_BATCH_SIZE=50
AI_MAX_CONCURRENT_REQUESTS=10
AI_CIRCUIT_BREAKER_THRESHOLD=5
AI_CIRCUIT_BREAKER_TIMEOUT=300

# Monitoring
AI_ENABLE_METRICS=true
AI_LOG_REQUESTS=true
AI_LOG_ERRORS=true
AI_PERFORMANCE_TRACKING=true

# Mail Configuration (Optional)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@jru.edu.ph"
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=local
```

#### Build Configuration

Railway will automatically detect this as a PHP/Laravel application. The build process will:

1. Install PHP dependencies via Composer
2. Install Node.js dependencies and build assets
3. Run database migrations
4. Cache configuration for production

#### Custom Build Command (Optional)

If you need custom build steps, add a `railway.json` file:

```json
{
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "composer install --optimize-autoloader --no-dev && npm ci && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=$PORT"
  }
}
```

#### Database Setup

1. Add a PostgreSQL database to your Railway project
2. The database variables will be automatically injected
3. Run migrations after deployment:

```bash
railway run php artisan migrate --force
railway run php artisan db:seed --class=AdminSeeder
```

## 2. Flask AI Service Deployment

### Railway Configuration

Add a second service to your Railway project for the AI service:

#### Environment Variables (AI Service)

```env
FLASK_PORT=5000
FLASK_DEBUG=false
LARAVEL_BASE_URL=https://your-laravel-app-url.up.railway.app
MODEL_SAVE_PATH=models/
LOG_LEVEL=INFO
```

#### Build Configuration

Railway will detect this as a Python application. The build process will:

1. Install Python dependencies from `requirements.txt`
2. Copy model files and data
3. Start the Flask application

#### Custom Build Command

Add a `railway.json` file in the `ai-service/` directory:

```json
{
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "pip install -r requirements.txt"
  },
  "deploy": {
    "startCommand": "python app.py"
  }
}
```

## 3. Service Communication

### Internal Networking

Railway services can communicate internally using their service URLs. Update the Laravel environment variables to use the AI service URL:

```env
FLASK_AI_SERVICE_URL=https://ai-service-name.up.railway.app
```

### Health Checks

Both services include health check endpoints:
- Laravel: `GET /health` (you may need to add this route)
- AI Service: `GET /health`

Configure health checks in Railway dashboard for each service.

## 4. File Storage & Assets

### Static Assets

Laravel's public assets (CSS, JS, images) will be served directly by Railway. The build process compiles and optimizes these files.

### Model Files & Data

The AI service requires model files and training data. These are included in the repository and will be deployed with the service.

## 5. Database Migration

After both services are deployed:

1. Connect to your Railway project via CLI:
```bash
railway login
railway link
```

2. Run database migrations:
```bash
railway run php artisan migrate --force
railway run php artisan db:seed --class=AdminSeeder
```

3. Generate application key:
```bash
railway run php artisan key:generate
```

## 6. Environment-Specific Configuration

### Production Optimizations

Ensure these settings for production:

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

### SSL & Security

Railway automatically provides SSL certificates. All traffic is encrypted.

## 7. Monitoring & Maintenance

### Logs

Access logs through Railway dashboard or CLI:
```bash
railway logs
```

### Scaling

Railway automatically scales services based on traffic. For the AI service, consider:
- CPU-intensive workloads may require higher instance sizes
- Memory usage depends on model size and concurrent requests

### Backups

Railway provides automatic database backups. Configure backup schedules in the database settings.

## 8. Troubleshooting

### Common Issues

1. **AI Service Connection**: Verify the `FLASK_AI_SERVICE_URL` is correct
2. **Database Connection**: Check Railway database variables are properly set
3. **Asset Compilation**: Ensure Node.js build process completes successfully
4. **Model Loading**: Verify model files are present in the AI service

### Debugging

Use Railway's log viewer to debug issues:
```bash
railway logs --service laravel-app
railway logs --service ai-service
```

## 9. Cost Optimization

### Resource Allocation

- Start with minimal instance sizes and scale up as needed
- Monitor usage patterns to optimize resource allocation
- Consider Railway's usage-based pricing

### Service Dependencies

- AI service may have higher resource requirements due to ML models
- Consider separating services if cost becomes an issue

## 10. Security Considerations

### Environment Variables

- Never commit sensitive data to version control
- Use Railway's secret management for sensitive variables
- Rotate API keys regularly

### Network Security

- Services communicate internally when possible
- Public endpoints should be properly secured
- Implement rate limiting if needed

## Deployment Checklist

- [ ] Create Railway project
- [ ] Add PostgreSQL database
- [ ] Deploy Laravel service with environment variables
- [ ] Deploy AI service with environment variables
- [ ] Run database migrations and seeding
- [ ] Test application functionality
- [ ] Configure domain (optional)
- [ ] Set up monitoring and alerts
- [ ] Test AI service integration
- [ ] Verify all endpoints work correctly

## Support

For Railway-specific issues:
- Railway Documentation: https://docs.railway.app/
- Railway Support: https://help.railway.app/

For application-specific issues:
- Check Laravel logs: `railway logs --service laravel-app`
- Check AI service logs: `railway logs --service ai-service`
- Verify environment variables are set correctly
