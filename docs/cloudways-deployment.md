# Cloudways Laravel Deployment Guide

## Prerequisites

- Cloudways account with PHP application server
- Domain name configured
- SSH access to server
- Git repository access

## Laravel Application Setup

### 1. Server Requirements

Ensure your Cloudways server meets these requirements:
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer installed
- Node.js and npm (for frontend assets)
- Redis (recommended for caching)

### 2. Application Deployment

#### Option A: Git-based Deployment

1. **Connect Repository**
   ```bash
   # In Cloudways dashboard, go to Application Settings > Deployment
   # Connect your Git repository
   # Set deployment branch (main/master)
   ```

2. **Environment Configuration**
   ```bash
   # SSH into your server
   cd applications/[app-name]/public_html

   # Copy environment file
   cp .env.example .env

   # Generate application key
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   # Create database in Cloudways dashboard
   # Update .env with database credentials
   DB_CONNECTION=mysql
   DB_HOST=[your-db-host]
   DB_PORT=3306
   DB_DATABASE=[your-db-name]
   DB_USERNAME=[your-db-user]
   DB_PASSWORD=[your-db-password]

   # Run migrations
   php artisan migrate --seed
   ```

#### Option B: Manual Upload

1. **Upload Files**
   ```bash
   # Upload project files to public_html directory
   # Or use Cloudways file manager
   ```

2. **Install Dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```

### 3. Environment Configuration

Update your `.env` file with production settings:

```env
APP_NAME="ISO 21001 Quality Education"
APP_ENV=production
APP_KEY=base64:[generated-key]
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=[cloudways-db-host]
DB_DATABASE=[database-name]
DB_USERNAME=[db-username]
DB_PASSWORD=[db-password]

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_HOST=[redis-host]
REDIS_PASSWORD=[redis-password]
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=[smtp-host]
MAIL_PORT=587
MAIL_USERNAME=[smtp-username]
MAIL_PASSWORD=[smtp-password]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

# AI Service (Fly.io)
FLASK_AI_SERVICE_URL=https://iso21001-ai-service.fly.dev
FLASK_AI_API_KEY=[your-api-key]
AI_TIMEOUT_SECONDS=30
AI_MAX_RETRIES=3
AI_ENABLE_CACHE=true
AI_CACHE_TTL=300
AI_FALLBACK_TO_PHP=true

# Queue Configuration (if using queues)
QUEUE_CONNECTION=database

# Performance Settings
AI_BATCH_SIZE=50
AI_MAX_CONCURRENT_REQUESTS=10
```

### 4. SSL Configuration

1. **Enable SSL**
   - Go to Cloudways dashboard
   - Navigate to Application Settings > SSL Certificate
   - Enable Let's Encrypt SSL or upload custom certificate

2. **Force HTTPS**
   ```bash
   # Update .env
   APP_URL=https://your-domain.com

   # Clear config cache
   php artisan config:clear
   php artisan config:cache
   ```

### 5. Performance Optimization

#### Caching
```bash
# Cache configuration and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Queue Setup (Optional)
```bash
# If using database queues
php artisan queue:table
php artisan migrate

# Start queue worker (configure in Cloudways cron)
# Add to cron: * * * * * php /home/master/applications/[app-name]/public_html/artisan queue:work --sleep=3 --tries=3
```

### 6. File Permissions

```bash
# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### 7. Cron Jobs Setup

Configure these cron jobs in Cloudways dashboard:

```bash
# Weekly progress reports (every Monday at 9 AM)
0 9 * * 1 php /home/master/applications/[app-name]/public_html/artisan weekly:progress:send

# Monthly compliance reports (1st of every month at 10 AM)
0 10 1 * * php /home/master/applications/[app-name]/public_html/artisan monthly:reports:generate

# Cache warmup (daily at 2 AM)
0 2 * * * php /home/master/applications/[app-name]/public_html/artisan cache:warmup

# Weekly metrics aggregation (every Sunday at 11 PM)
0 23 * * 0 php /home/master/applications/[app-name]/public_html/artisan weekly:metrics:aggregate
```

### 8. Monitoring & Logs

#### Application Logs
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# View PHP error logs (Cloudways)
tail -f /var/log/php/error.log
```

#### AI Service Monitoring
```bash
# Test AI service connectivity
php artisan ai:test-flask

# Check AI service status
php artisan tinker
>>> app(App\Services\FlaskAIClient::class)->getServiceStatus()
```

### 9. Backup Configuration

1. **Database Backups**
   - Configure automatic backups in Cloudways dashboard
   - Set backup frequency (daily/weekly)
   - Download backups regularly

2. **File Backups**
   - Include important directories: `storage/`, `public/uploads/`
   - Backup AI models if stored locally

### 10. Security Checklist

- [ ] SSL certificate installed and forced HTTPS
- [ ] Strong application key generated
- [ ] Database credentials secured
- [ ] File permissions set correctly
- [ ] Debug mode disabled
- [ ] Sensitive data not in logs
- [ ] Regular security updates enabled
- [ ] Firewall configured
- [ ] Backup system active

### 11. Testing Deployment

#### Health Checks
```bash
# Test application
curl -I https://your-domain.com

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Test AI service
php artisan ai:test-flask
```

#### Performance Testing
```bash
# Cache status
php artisan cache:stats

# Response time check
curl -o /dev/null -s -w "%{time_total}\n" https://your-domain.com
```

### 12. Troubleshooting

#### Common Issues

1. **500 Internal Server Error**
   ```bash
   # Check logs
   tail -f storage/logs/laravel.log

   # Clear caches
   php artisan config:clear
   php artisan cache:clear
   ```

2. **AI Service Connection Failed**
   ```bash
   # Test connectivity
   curl -I https://iso21001-ai-service.fly.dev/health

   # Check Laravel AI config
   php artisan config:show ai
   ```

3. **Database Connection Issues**
   ```bash
   # Test connection
   php artisan tinker
   >>> DB::connection()->getPdo()

   # Check credentials in .env
   ```

4. **Permission Issues**
   ```bash
   # Fix storage permissions
   chmod -R 755 storage/
   chown -R www-data:www-data storage/
   ```

### 13. Post-Deployment Tasks

1. **Update DNS**
   - Point domain to Cloudways server IP
   - Configure any subdomains

2. **SEO Setup**
   - Submit sitemap to search engines
   - Configure Google Analytics

3. **User Communication**
   - Notify stakeholders of new URL
   - Update any hardcoded URLs

4. **Monitoring Setup**
   - Configure uptime monitoring
   - Set up error alerting

---

## AI Service Integration

The Laravel application is configured to use the AI service deployed on Fly.io. Ensure:

1. Fly.io AI service is deployed and accessible
2. API keys are configured correctly
3. CORS settings allow Cloudways domain
4. SSL certificates are valid on both services

## Support

For deployment issues:
1. Check Cloudways documentation
2. Review Laravel deployment guides
3. Contact Cloudways support for server-specific issues
4. Check application logs for detailed error messages
