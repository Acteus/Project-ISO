# ISO 21001 Quality Education System - Deployment Guide

This guide covers deploying the Laravel application to Cloudways and the AI service to Fly.io for production use.

## Architecture Overview

```
┌─────────────────┐    HTTPS    ┌─────────────────┐
│   Cloudways     │────────────▶│     Fly.io      │
│   Laravel App   │             │   AI Service    │
│                 │◀────────────│                 │
│ - Web Interface │    API      │ - ML Models     │
│ - Admin Panel   │             │ - Predictions   │
│ - Database      │             │ - Analytics     │
│ - Reports       │             │                 │
└─────────────────┘             └─────────────────┘
```

## Quick Start

### 1. Deploy AI Service to Fly.io

```bash
cd ai-service
fly launch --name iso21001-ai-service
fly deploy
```

### 2. Deploy Laravel App to Cloudways

1. Create Cloudways account and server
2. Connect Git repository
3. Update `.env` with production settings
4. Run migrations and seeders

### 3. Configure Integration

Update Laravel `.env`:
```env
FLASK_AI_SERVICE_URL=https://iso21001-ai-service.fly.dev
FLASK_AI_API_KEY=your-api-key
```

### 4. Test Deployment

```bash
# Test AI service connectivity
php artisan ai:test-connection

# Test all AI models
php artisan ai:test-connection --service=all --detailed
```

## Detailed Deployment Steps

### AI Service Deployment (Fly.io)

See [`fly-deployment.md`](./fly-deployment.md) for complete Fly.io deployment instructions.

**Key Files:**
- `ai-service/fly.toml` - Fly.io configuration
- `ai-service/Dockerfile.fly` - Production Docker setup
- `ai-service/.env.fly` - Production environment variables

### Laravel Application Deployment (Cloudways)

See [`cloudways-deployment.md`](./cloudways-deployment.md) for complete Cloudways deployment instructions.

**Key Configuration:**
- Update `FLASK_AI_SERVICE_URL` in `.env`
- Configure database credentials
- Set up SSL certificates
- Configure cron jobs

## Environment Configuration

### Production .env Template

```env
# Application
APP_NAME="ISO 21001 Quality Education"
APP_ENV=production
APP_KEY=base64:[generated-key]
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (Cloudways)
DB_CONNECTION=mysql
DB_HOST=[cloudways-db-host]
DB_DATABASE=[database-name]
DB_USERNAME=[db-username]
DB_PASSWORD=[db-password]

# AI Service (Fly.io)
FLASK_AI_SERVICE_URL=https://iso21001-ai-service.fly.dev
FLASK_AI_API_KEY=[your-api-key]
AI_TIMEOUT_SECONDS=30
AI_MAX_RETRIES=3
AI_ENABLE_CACHE=true

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_HOST=[redis-host]
REDIS_PASSWORD=[redis-password]

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=[smtp-host]
MAIL_PORT=587
MAIL_USERNAME=[smtp-username]
MAIL_PASSWORD=[smtp-password]
MAIL_ENCRYPTION=tls
```

## Testing & Validation

### Pre-Deployment Testing

```bash
# Test local AI service
cd ai-service && python app.py

# Test Laravel AI integration
php artisan ai:test-connection --detailed
```

### Post-Deployment Testing

```bash
# Test production AI service
curl https://iso21001-ai-service.fly.dev/health

# Test Laravel application
curl -I https://your-domain.com

# Test AI integration
php artisan ai:test-connection --service=all
```

## Monitoring & Maintenance

### AI Service Monitoring

- Health checks: `GET /health`
- Logs: `fly logs`
- Metrics: `fly status`

### Laravel Application Monitoring

- Application logs: `storage/logs/laravel.log`
- Queue monitoring: `php artisan queue:status`
- Cache monitoring: `php artisan cache:stats`

## Security Considerations

### AI Service Security

- API key authentication
- CORS configuration for Laravel domain
- HTTPS enforcement
- Model file protection

### Laravel Security

- SSL/TLS certificates
- Strong application keys
- Secure database credentials
- File permission management
- Regular security updates

## Performance Optimization

### AI Service

- Scale instances: `fly scale count 2-3`
- Monitor memory usage
- Optimize model loading
- Use persistent volumes for models

### Laravel Application

- Enable OPcache
- Configure Redis caching
- Set up CDN for static assets
- Optimize database queries
- Enable compression

## Backup & Recovery

### AI Service Backups

- Model files backup
- Configuration backup
- Redeploy capability

### Laravel Backups

- Database backups (Cloudways automated)
- File system backups
- Configuration backups

## Troubleshooting

### Common Issues

1. **AI Service Connection Failed**
   ```bash
   # Check service status
   curl https://iso21001-ai-service.fly.dev/health

   # Test from Laravel
   php artisan ai:test-connection
   ```

2. **Laravel Deployment Issues**
   - Check file permissions
   - Verify environment variables
   - Check database connectivity

3. **Performance Issues**
   - Monitor resource usage
   - Check logs for bottlenecks
   - Scale services as needed

## Support & Resources

- [Fly.io Documentation](https://fly.io/docs/)
- [Cloudways Documentation](https://support.cloudways.com/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [AI Service README](./ai-service/README.md)

## Deployment Checklist

### AI Service (Fly.io)
- [ ] Fly CLI installed and authenticated
- [ ] AI service code prepared
- [ ] Models trained and available
- [ ] Environment variables configured
- [ ] App deployed successfully
- [ ] Health checks passing
- [ ] SSL certificate active

### Laravel Application (Cloudways)
- [ ] Cloudways server created
- [ ] Git repository connected
- [ ] Environment variables configured
- [ ] Database migrated and seeded
- [ ] SSL certificate installed
- [ ] Cron jobs configured
- [ ] File permissions set
- [ ] Caching configured

### Integration Testing
- [ ] AI service connectivity verified
- [ ] All AI models tested
- [ ] Admin panel accessible
- [ ] Survey forms working
- [ ] Reports generating
- [ ] Email notifications working

---

## Quick Commands Reference

### AI Service
```bash
# Deploy
fly deploy

# Monitor
fly status
fly logs

# Scale
fly scale count 2
```

### Laravel Application
```bash
# Cache management
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test AI connection
php artisan ai:test-connection

# Queue management
php artisan queue:work
php artisan queue:status
```

## Final Notes

- Always test thoroughly before going live
- Monitor performance and scale as needed
- Keep backups current and tested
- Update dependencies regularly
- Monitor logs for security issues

For detailed instructions, refer to the specific deployment guides:
- [Fly.io AI Service Deployment](./fly-deployment.md)
- [Cloudways Laravel Deployment](./cloudways-deployment.md)
