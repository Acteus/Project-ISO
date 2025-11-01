# Railway Monorepo Deployment Guide
## ISO 21001 Survey System with AI Service

This guide provides complete instructions for deploying the Jose Rizal University ISO 21001 Student Survey System to Railway as a monorepo containing both the Laravel application and the Flask AI service with 8 machine learning models.

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Architecture](#architecture)
4. [Step-by-Step Deployment](#step-by-step-deployment)
5. [Environment Variables](#environment-variables)
6. [Post-Deployment Configuration](#post-deployment-configuration)
7. [Monitoring & Maintenance](#monitoring--maintenance)
8. [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

### System Components

This monorepo contains two main services:

1. **Laravel Application** (Main Service)
   - Student survey forms
   - Admin dashboard
   - Analytics and reporting
   - QR code management
   - User authentication
   
2. **Flask AI Service** (Microservice)
   - 8 Machine Learning Models:
     1. ISO 21001 Compliance Predictor
     2. Sentiment Analyzer (NLP)
     3. Student Clusterer (K-Means/DBSCAN)
     4. Student Performance Predictor
     5. Dropout Risk Predictor
     6. Risk Assessment Predictor
     7. Satisfaction Trend Predictor
     8. Predictive Analytics Engine

### Deployment Strategy

Both services will be deployed as separate Railway services from the same GitHub repository, using Railway's monorepo support with "Root Directory" configuration.

---

## ✅ Prerequisites

Before starting, ensure you have:

- [ ] Railway account (https://railway.app)
- [ ] GitHub repository with this code pushed
- [ ] Railway CLI installed (optional but recommended)
- [ ] Basic understanding of Railway's platform

### Install Railway CLI (Optional)

```bash
# macOS/Linux
curl -fsSL https://railway.app/install.sh | sh

# Windows (PowerShell)
iwr https://railway.app/install.ps1 -useb | iex
```

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Railway Project                          │
│                                                             │
│  ┌──────────────────────┐      ┌─────────────────────────┐ │
│  │  Laravel Service     │      │   Flask AI Service      │ │
│  │  (Root: /)          │◄─────►│   (Root: /ai-service)  │ │
│  │                      │      │                         │ │
│  │  - PHP 8.2          │      │   - Python 3.11         │ │
│  │  - Laravel 11       │      │   - 8 ML Models         │ │
│  │  - Node.js 20       │      │   - Gunicorn            │ │
│  │  - Composer         │      │   - TensorFlow          │ │
│  │  - NPM/Vite         │      │   - scikit-learn        │ │
│  └──────────────────────┘      └─────────────────────────┘ │
│           │                              │                  │
│           │                              │                  │
│  ┌────────▼──────────────────────────────▼────────────────┐ │
│  │              PostgreSQL Database                       │ │
│  │              (Shared by Laravel)                       │ │
│  └───────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

---

## 🚀 Step-by-Step Deployment

### Step 1: Create a New Railway Project

1. Log in to Railway: https://railway.app
2. Click **"New Project"**
3. Choose **"Deploy from GitHub repo"**
4. Select your repository: `Project-ISO`
5. Railway will detect the codebase

### Step 2: Add PostgreSQL Database

1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"PostgreSQL"**
3. Railway will provision and configure the database
4. Note: Database variables (`PGHOST`, `PGPORT`, etc.) are automatically available

### Step 3: Deploy Laravel Service

#### 3.1 Configure the Laravel Service

1. In your Railway project, you should see the service created from your repo
2. Rename it to **"laravel-app"** (click the service name to edit)
3. Go to **"Settings"** tab
4. Set **"Root Directory"** to `/` (or leave empty)
5. Railway will detect `railway.json` and use it

#### 3.2 Set Environment Variables for Laravel

Go to the **"Variables"** tab and add the following:

```env
# Application
APP_NAME=Jose Rizal University - ISO 21001 Survey System
APP_ENV=production
APP_DEBUG=false
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}

# Generate APP_KEY by running: php artisan key:generate --show
# CRITICAL: Set a fixed APP_KEY to prevent CSRF token mismatches
APP_KEY=base64:YOUR_GENERATED_KEY_HERE

# Database (These are automatically set by Railway PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=${{PGHOST}}
DB_PORT=${{PGPORT}}
DB_DATABASE=${{PGDATABASE}}
DB_USERNAME=${{PGUSER}}
DB_PASSWORD=${{PGPASSWORD}}

# Session & Cache (CRITICAL for CSRF to work)
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_HTTP_ONLY=true
# Leave SESSION_DOMAIN empty for Railway domains (or set to your custom domain without protocol)
CACHE_STORE=database
QUEUE_CONNECTION=database

# AI Service URL (will be set after AI service is deployed)
FLASK_AI_SERVICE_URL=https://ai-service.railway.internal
AI_TIMEOUT_SECONDS=30
AI_ENABLE_CACHE=true
AI_FALLBACK_TO_PHP=true

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jru.edu.ph

# Sanctum (for API authentication)
SANCTUM_STATEFUL_DOMAINS=${{RAILWAY_PUBLIC_DOMAIN}},localhost,127.0.0.1

# Logging
LOG_LEVEL=error
```

#### 3.3 Generate Application Key (CRITICAL - REQUIRED!)

**🚨 THIS IS THE #1 CAUSE OF 500 ERRORS - DO NOT SKIP!**

Laravel **REQUIRES** an `APP_KEY` to function. Without it, you'll get a 500 server error.

```bash
# On your local machine, generate a key
php artisan key:generate --show

# Example output: base64:abcdefghijklmnopqrstuvwxyz1234567890ABCD=
```

**Steps:**
1. Run the command above on your local machine
2. Copy the ENTIRE output including the `base64:` prefix
3. Go to Railway → Your Laravel service → **Variables** tab
4. Add a new variable:
   - **Name**: `APP_KEY`
   - **Value**: `base64:abcdefghijklmnopqrstuvwxyz1234567890ABCD=` (your actual key)
5. Click **Save**

**Why this matters:**
- ❌ **Without `APP_KEY`**: Laravel will throw a 500 error immediately
- ✅ **With fixed `APP_KEY`**: Application works and sessions remain valid
- 🔄 **Changing `APP_KEY`**: Invalidates all sessions; users must re-login

**Symptoms of missing `APP_KEY`:**
- `500 Server Error` on all pages
- Railway logs show: `RuntimeException: No application encryption key has been specified`
- Health check fails

#### 3.4 Deploy Laravel Service

1. Click **"Deploy"** or push to your GitHub repo
2. Railway will automatically build and deploy
3. Monitor the deployment logs
4. Once deployed, note the public URL

### Step 4: Deploy Flask AI Service

#### 4.1 Add Second Service to Project

1. In your Railway project, click **"+ New"**
2. Select **"GitHub Repo"** → Choose the same repository
3. Rename the service to **"ai-service"**

#### 4.2 Configure AI Service

1. Go to **"Settings"** tab
2. Set **"Root Directory"** to `ai-service`
3. Railway will detect `ai-service/railway.json`

#### 4.3 Set Environment Variables for AI Service

Go to the **"Variables"** tab and add:

```env
# Flask Configuration
FLASK_PORT=5000
FLASK_DEBUG=false
FLASK_ENV=production

# Laravel Base URL (use the Laravel service URL)
LARAVEL_BASE_URL=${{laravel-app.RAILWAY_PUBLIC_DOMAIN}}

# Model Configuration
MODEL_SAVE_PATH=models/
LOG_LEVEL=INFO

# Performance
MAX_WORKERS=4
TIMEOUT_SECONDS=60
```

#### 4.4 Deploy AI Service

1. Click **"Deploy"**
2. Railway will:
   - Install Python dependencies (TensorFlow, scikit-learn, etc.)
   - Download NLTK data
   - Load the 8 ML models
   - Start Gunicorn server
3. Monitor deployment logs (this may take 3-5 minutes due to ML dependencies)
4. Note the public or internal URL

### Step 5: Connect Services

#### 5.1 Update Laravel Environment Variable

1. Go back to **"laravel-app"** service
2. Update the `FLASK_AI_SERVICE_URL` variable:

```env
# Option 1: Use Railway's internal networking (recommended)
FLASK_AI_SERVICE_URL=https://ai-service.railway.internal

# Option 2: Use public URL
FLASK_AI_SERVICE_URL=${{ai-service.RAILWAY_PUBLIC_DOMAIN}}
```

3. Redeploy the Laravel service to apply changes

### Step 6: Run Database Migrations

#### Option A: Using Railway CLI

```bash
# Link to your project
railway link

# Run migrations
railway run -s laravel-app php artisan migrate --force

# Seed admin user
railway run -s laravel-app php artisan db:seed --class=AdminSeeder
```

#### Option B: Using Railway Dashboard

1. Go to **"laravel-app"** service
2. Open the **"Settings"** tab
3. Find **"Deploy Command"** or use the **"Shell"** feature
4. Run:
```bash
php artisan migrate --force
php artisan db:seed --class=AdminSeeder
```

---

## 🔐 Environment Variables

### Laravel Service - Complete List

| Variable | Description | Example | Required |
|----------|-------------|---------|----------|
| `APP_NAME` | Application name | Jose Rizal University | Yes |
| `APP_ENV` | Environment | production | Yes |
| `APP_KEY` | Encryption key | base64:... | Yes |
| `APP_DEBUG` | Debug mode | false | Yes |
| `APP_URL` | Application URL | ${{RAILWAY_PUBLIC_DOMAIN}} | Yes |
| `DB_CONNECTION` | Database type | pgsql | Yes |
| `DB_HOST` | Database host | ${{PGHOST}} | Yes |
| `DB_PORT` | Database port | ${{PGPORT}} | Yes |
| `DB_DATABASE` | Database name | ${{PGDATABASE}} | Yes |
| `DB_USERNAME` | Database user | ${{PGUSER}} | Yes |
| `DB_PASSWORD` | Database password | ${{PGPASSWORD}} | Yes |
| `FLASK_AI_SERVICE_URL` | AI service URL | https://ai-service.railway.internal | Yes |
| `MAIL_MAILER` | Mail driver | smtp | No |
| `MAIL_HOST` | SMTP host | smtp.gmail.com | No |
| `MAIL_PORT` | SMTP port | 587 | No |
| `MAIL_USERNAME` | Email address | your-email@gmail.com | No |
| `MAIL_PASSWORD` | Email password | your-app-password | No |

### Flask AI Service - Complete List

| Variable | Description | Example | Required |
|----------|-------------|---------|----------|
| `FLASK_PORT` | Flask port | 5000 | Yes |
| `FLASK_DEBUG` | Debug mode | false | Yes |
| `FLASK_ENV` | Environment | production | Yes |
| `LARAVEL_BASE_URL` | Laravel URL | ${{laravel-app.RAILWAY_PUBLIC_DOMAIN}} | Yes |
| `MODEL_SAVE_PATH` | Models directory | models/ | Yes |
| `LOG_LEVEL` | Logging level | INFO | Yes |

---

## ⚙️ Post-Deployment Configuration

### 1. Test Health Endpoints

```bash
# Test Laravel (primary health check)
curl https://your-laravel-app.up.railway.app/up

# Test Laravel (alternative endpoint)
curl https://your-laravel-app.up.railway.app/health

# Test AI Service
curl https://your-ai-service.up.railway.app/health
```

Expected response:
```json
{"status": "ok"}
```

### 2. Test AI Service Integration

```bash
# Using Railway CLI
railway run -s laravel-app php artisan ai:test-flask
```

Or test via Laravel application:
1. Log in to admin dashboard
2. Navigate to **"AI Insights"** page
3. Check if AI metrics are loading

### 3. Configure Custom Domain (Optional)

1. Go to **"laravel-app"** → **"Settings"** → **"Domains"**
2. Click **"Generate Domain"** or add custom domain
3. Update `APP_URL` environment variable
4. Update `SANCTUM_STATEFUL_DOMAINS` if using API

### 4. Set Up Health Checks

Railway automatically monitors the health check endpoints:
- Laravel: `/health`
- AI Service: `/health`

Configure in **"Settings"** → **"Health Check"**:
- Path: `/health`
- Timeout: 300 seconds
- Interval: 30 seconds

---

## 📊 Monitoring & Maintenance

### Access Logs

```bash
# Laravel logs
railway logs -s laravel-app

# AI Service logs
railway logs -s ai-service

# Follow logs in real-time
railway logs -s laravel-app --follow
```

### Metrics Monitoring

1. Go to Railway Dashboard
2. Select each service
3. View the **"Metrics"** tab:
   - CPU usage
   - Memory usage
   - Network traffic
   - Request count

### Scaling Considerations

#### Laravel Service
- **CPU**: 1-2 vCPUs recommended
- **Memory**: 512MB - 1GB
- **Replicas**: Start with 1, scale based on traffic

#### AI Service
- **CPU**: 1-2 vCPUs (ML models are CPU-intensive)
- **Memory**: 1-2GB (ML models require more memory)
- **Replicas**: 1-2 (based on usage)

### Database Backups

Railway provides automatic PostgreSQL backups:
1. Go to **"PostgreSQL"** service
2. Check **"Backups"** tab
3. Configure backup retention policy

---

## 🔧 Troubleshooting

### Common Issues

#### 0. 500 Server Error - Application Fails to Load

**Problem**: All pages return `500 Internal Server Error`

**Most Common Cause**: Missing or invalid `APP_KEY`

**Solution**:
```bash
# Step 1: Generate a key locally
php artisan key:generate --show

# Step 2: Copy the output (e.g., base64:xyz123...)

# Step 3: Set it in Railway
# Go to Railway → Laravel service → Variables → Add Variable
# Name: APP_KEY
# Value: base64:xyz123... (paste your key)

# Step 4: Check Railway logs for errors
railway logs -s laravel-app

# Step 5: Clear cache if needed
railway run -s laravel-app php artisan config:clear
railway run -s laravel-app php artisan cache:clear
```

**Other possible causes:**
- Database connection failed (check `DB_*` variables)
- Missing storage directories (should be auto-created by `start.sh`)
- PHP extensions missing (check Railway build logs)
- Syntax errors in code (check Railway logs)

**Check Railway logs to see the actual error:**
```bash
railway logs -s laravel-app --follow
```

Look for lines like:
- `RuntimeException: No application encryption key has been specified`
- `SQLSTATE[HY000]` (database connection error)
- `PHP Fatal error:` (code errors)

#### 1. Laravel Build Fails

**Problem**: Composer or NPM dependencies fail to install

**Solution**:
```bash
# Check composer.json and package.json are valid
# Clear Railway cache and redeploy
```

In Railway dashboard:
- Settings → Clear Build Cache → Redeploy

#### 2. AI Service Out of Memory

**Problem**: AI service crashes with OOM error

**Solution**:
- Increase memory allocation in Railway settings
- Reduce number of Gunicorn workers in `railway.json`:
```json
"startCommand": "gunicorn --workers 1 --threads 2 ..."
```

#### 3. Database Connection Failed

**Problem**: Laravel cannot connect to PostgreSQL

**Solution**:
- Verify database variables are set correctly
- Check `DB_CONNECTION=pgsql` (not `mysql`)
- Restart the Laravel service

#### 4. AI Service Not Responding

**Problem**: Laravel cannot reach AI service

**Solution**:
```bash
# Check AI service is running
railway logs -s ai-service

# Verify FLASK_AI_SERVICE_URL is correct
# Test health endpoint
curl https://ai-service-url/health

# Check firewall/network settings
```

#### 5. Models Not Loading

**Problem**: ML models fail to load in AI service

**Solution**:
- Ensure all `.pkl` files are in `ai-service/models/` directory
- Check files are committed to Git (not ignored)
- Verify model files are not corrupted:
```bash
# List models
ls -lh ai-service/models/
```

#### 6. Session Issues

**Problem**: Users logged out frequently

**Solution**:
- Set `SESSION_DRIVER=database` (not `file`)
- Configure session domain correctly
- Check `SESSION_SECURE_COOKIE=true` for HTTPS

### Debug Commands

```bash
# Check environment variables
railway run -s laravel-app env

# Test database connection
railway run -s laravel-app php artisan db:show

# Check migration status
railway run -s laravel-app php artisan migrate:status

# Clear all caches
railway run -s laravel-app php artisan cache:clear
railway run -s laravel-app php artisan config:clear
railway run -s laravel-app php artisan route:clear
railway run -s laravel-app php artisan view:clear

# Test AI service
railway run -s laravel-app php artisan ai:test-flask
```

### Getting Help

1. **Railway Documentation**: https://docs.railway.app
2. **Railway Community**: https://discord.gg/railway
3. **Check Service Logs**: `railway logs -s <service-name>`
4. **Laravel Logs**: Check in Railway dashboard or via CLI

---

## 🎉 Deployment Checklist

Use this checklist to ensure complete deployment:

### Pre-Deployment
- [ ] Code pushed to GitHub repository
- [ ] `.env.example` files updated
- [ ] `railway.json` files configured
- [ ] ML model files present in `ai-service/models/`
- [ ] Database migrations tested locally

### Railway Setup
- [ ] Railway project created
- [ ] PostgreSQL database added
- [ ] Laravel service configured with root directory `/`
- [ ] AI service configured with root directory `ai-service`
- [ ] **`APP_KEY` generated and set** (CRITICAL - will cause 500 error if missing)
- [ ] All other environment variables set for Laravel
- [ ] All environment variables set for AI service
- [ ] Services deployed successfully

### Post-Deployment
- [ ] Database migrations run
- [ ] Admin user seeded
- [ ] Health endpoints tested (Laravel & AI)
- [ ] AI service integration tested
- [ ] Login functionality verified
- [ ] Survey submission tested
- [ ] Admin dashboard accessible
- [ ] AI insights loading correctly
- [ ] Email notifications working (if configured)
- [ ] Custom domain configured (if needed)

### Monitoring Setup
- [ ] Health checks configured
- [ ] Log monitoring set up
- [ ] Metrics dashboards reviewed
- [ ] Backup policy configured
- [ ] Alerts configured (optional)

---

## 📚 Additional Resources

### Railway Documentation
- Main Docs: https://docs.railway.app
- Monorepo Guide: https://docs.railway.app/deploy/monorepo
- Environment Variables: https://docs.railway.app/develop/variables
- Private Networking: https://docs.railway.app/deploy/private-networking

### Laravel on Railway
- Laravel Deployment: https://docs.railway.app/guides/laravel
- PostgreSQL Setup: https://docs.railway.app/databases/postgresql

### Python/Flask on Railway
- Python Deployment: https://docs.railway.app/guides/python
- Nixpacks Python: https://nixpacks.com/docs/providers/python

---

## 🔒 Security Best Practices

1. **Never commit `.env` files** to Git
2. **Use strong `APP_KEY`** for Laravel (32+ characters)
3. **Enable HTTPS only** for production (`SESSION_SECURE_COOKIE=true`)
4. **Disable debug mode** in production (`APP_DEBUG=false`)
5. **Use Railway's private networking** for service communication
6. **Rotate secrets regularly** (database passwords, API keys)
7. **Set up Railway's SOC 2 compliance** if handling sensitive data
8. **Enable database backups** and test restoration
9. **Monitor logs** for suspicious activity
10. **Keep dependencies updated** regularly

---

## 📞 Support

For deployment issues:
- **Railway Support**: https://help.railway.app
- **Railway Discord**: https://discord.gg/railway
- **Check Service Status**: https://railway.statuspage.io

For application issues:
- Check application logs in Railway dashboard
- Review Laravel logs: `railway logs -s laravel-app`
- Review AI service logs: `railway logs -s ai-service`

---

**Last Updated**: November 1, 2025  
**Version**: 1.0.0  
**Maintained by**: Kwadra Team

---

Happy Deploying! 🚀

