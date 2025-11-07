# Fly.io AI Service Deployment Guide

## Overview

This guide covers deploying the Flask AI service to Fly.io for production use with the Laravel ISO 21001 application.

## Prerequisites

- Fly.io account and CLI installed
- Docker installed locally
- Git repository access
- Trained AI models in `ai-service/models/`

## Deployment Steps

### 1. Install Fly.io CLI

```bash
# macOS
brew install flyctl

# Linux
curl -L https://fly.io/install.sh | sh

# Verify installation
fly version
```

### 2. Authenticate with Fly.io

```bash
fly auth login
```

### 3. Prepare the AI Service

```bash
cd ai-service

# Copy production environment file
cp .env.fly .env

# Update with your production settings
nano .env
```

### 4. Deploy to Fly.io

#### Option A: Using fly.toml (Recommended)

```bash
# Initialize Fly app
fly launch --name iso21001-ai-service

# Or if fly.toml exists, deploy directly
fly deploy
```

#### Option B: Manual Deployment

```bash
# Create app
fly apps create iso21001-ai-service

# Set secrets (if needed)
fly secrets set FLASK_AI_API_KEY=your-production-api-key
fly secrets set SECRET_KEY=your-production-secret-key

# Deploy
fly deploy
```

### 5. Configure Environment Variables

```bash
# Set production environment variables
fly secrets set FLASK_AI_SERVICE_URL=https://iso21001-ai-service.fly.dev
fly secrets set LARAVEL_BASE_URL=https://your-cloudways-domain.com
fly secrets set CORS_ORIGINS=https://your-cloudways-domain.com

# Optional: Set API key for Laravel communication
fly secrets set LARAVEL_API_KEY=your-secure-api-key
```

### 6. Volume Setup (For Model Persistence)

```bash
# Create persistent volumes for models and data
fly volumes create models_cache --size 10
fly volumes create data_cache --size 5

# Deploy with volumes
fly deploy
```

### 7. Health Checks

```bash
# Check app status
fly status

# View logs
fly logs

# Check health endpoint
curl https://iso21001-ai-service.fly.dev/health
```

### 8. Scale the Application

```bash
# Scale to multiple instances
fly scale count 2

# Check scaling status
fly status
```

## Configuration Files

### fly.toml

The `fly.toml` file configures:
- Build settings with Paketo buildpacks
- Environment variables
- HTTP services on port 8080
- Health checks
- Persistent volumes for models

### Dockerfile.fly

Alternative Docker-based deployment:
- Uses Python 3.11 slim base image
- Optimized for production
- Includes health checks
- Exposes port 8080

### .env.fly

Production environment configuration:
- Flask settings for production
- CORS settings for Laravel domain
- Logging configuration
- Performance settings

## Model Management

### Uploading Trained Models

```bash
# If models aren't in git, upload them after deployment
fly ssh console

# Inside container, you can upload models via SCP or other methods
# Models should be in the models/ directory
```

### Model Updates

```bash
# To update models:
# 1. Train new models locally
# 2. Copy to ai-service/models/
# 3. Deploy: fly deploy

# Or update via SSH:
fly ssh console
# Upload new model files to /app/models/
```

## Monitoring & Maintenance

### Logs

```bash
# View application logs
fly logs

# Stream logs in real-time
fly logs -f
```

### Metrics

```bash
# View app metrics
fly status

# Check resource usage
fly scale show
```

### Health Monitoring

```bash
# Test health endpoint
curl -f https://iso21001-ai-service.fly.dev/health

# Test AI endpoints
curl -X POST https://iso21001-ai-service.fly.dev/api/v1/compliance/predict \
  -H "Content-Type: application/json" \
  -d '{"learner_needs_index": 4.0, "satisfaction_score": 3.8, "success_index": 4.1, "safety_index": 4.5, "wellbeing_index": 3.9, "overall_satisfaction": 4.0}'
```

## Security Configuration

### API Keys

```bash
# Set API key for Laravel authentication
fly secrets set LARAVEL_API_KEY=your-secure-api-key-here
```

### CORS Settings

```bash
# Restrict CORS to your Laravel domain
fly secrets set CORS_ORIGINS=https://your-production-domain.com
```

### Network Security

Fly.io automatically provides:
- HTTPS/TLS termination
- DDoS protection
- Firewall rules
- Private networking

## Troubleshooting

### Common Issues

1. **Build Failures**
   ```bash
   # Check build logs
   fly logs

   # Rebuild with verbose output
   fly deploy --verbose
   ```

2. **Model Loading Errors**
   ```bash
   # Check if models exist in container
   fly ssh console
   ls -la /app/models/

   # Verify model files are not corrupted
   ```

3. **Memory Issues**
   ```bash
   # Increase memory if needed
   fly scale memory 1024

   # Check current scaling
   fly scale show
   ```

4. **Health Check Failures**
   ```bash
   # Test health endpoint manually
   curl https://iso21001-ai-service.fly.dev/health

   # Check Flask logs for errors
   fly logs
   ```

### Performance Optimization

```bash
# Scale based on load
fly scale count 3

# Increase memory for large models
fly scale memory 2048

# Add more CPU
fly scale vm shared-cpu-2x
```

## Integration with Laravel

### Update Laravel Configuration

In your Laravel `.env` file:

```env
FLASK_AI_SERVICE_URL=https://iso21001-ai-service.fly.dev
FLASK_AI_API_KEY=your-api-key-here
AI_TIMEOUT_SECONDS=30
AI_MAX_RETRIES=3
```

### Test Integration

```bash
# From Laravel project directory
php artisan ai:test-flask
```

## Cost Optimization

### Scaling

- Start with 1 instance for development/testing
- Scale to 2-3 instances for production
- Use auto-scaling based on CPU/memory usage

### Storage

- Use Fly volumes for model persistence
- Monitor storage usage regularly
- Clean up old model versions

## Backup & Recovery

### Model Backups

```bash
# Download models for backup
fly ssh console
tar -czf models_backup.tar.gz /app/models/
# Download via fly ssh sftp or other methods
```

### App Recovery

```bash
# Quick restart
fly restart

# Full redeploy
fly deploy
```

## Support & Resources

- [Fly.io Documentation](https://fly.io/docs/)
- [Fly.io Status](https://status.fly.io/)
- [Laravel AI Integration Guide](./README-FLAK-AI-INTEGRATION.md)

## Deployment Checklist

- [ ] Fly CLI installed and authenticated
- [ ] AI service code prepared
- [ ] Environment variables configured
- [ ] Models uploaded to service
- [ ] App deployed successfully
- [ ] Health checks passing
- [ ] Laravel integration tested
- [ ] SSL certificate active
- [ ] Monitoring configured
- [ ] Backups scheduled

---

## Quick Deployment Commands

```bash
# One-time setup
fly auth login
cd ai-service
cp .env.fly .env

# Deploy
fly launch --name iso21001-ai-service
fly deploy

# Configure
fly secrets set LARAVEL_API_KEY=your-key
fly secrets set CORS_ORIGINS=https://your-domain.com

# Monitor
fly status
fly logs -f
