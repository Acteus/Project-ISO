# Railway Quick Start Guide
## Deploy in 15 Minutes

This is a condensed guide for quickly deploying the ISO 21001 Survey System to Railway.

---

## 🚀 Quick Deploy Steps

### 1. Create Railway Project (2 min)

```bash
# Install Railway CLI
curl -fsSL https://railway.app/install.sh | sh

# Login
railway login

# Create new project
railway init
```

Or use Railway Dashboard:
1. Go to https://railway.app
2. Click "New Project" → "Deploy from GitHub"
3. Select your repository

### 2. Add PostgreSQL (1 min)

1. Click "+ New" → "Database" → "PostgreSQL"
2. Railway auto-configures database variables

### 3. Deploy Laravel (5 min)

**Service Configuration:**
- Name: `laravel-app`
- Root Directory: `/`

**Essential Environment Variables:**
```env
APP_NAME=ISO 21001 Survey System
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE  # Generate with: php artisan key:generate --show
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

DB_CONNECTION=pgsql
DB_HOST=${{PGHOST}}
DB_PORT=${{PGPORT}}
DB_DATABASE=${{PGDATABASE}}
DB_USERNAME=${{PGUSER}}
DB_PASSWORD=${{PGPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database

FLASK_AI_SERVICE_URL=https://ai-service.railway.internal
```

**Deploy:**
```bash
railway up
```

### 4. Deploy AI Service (5 min)

**Service Configuration:**
- Name: `ai-service`
- Root Directory: `ai-service`

**Essential Environment Variables:**
```env
FLASK_PORT=5000
FLASK_DEBUG=false
FLASK_ENV=production
LARAVEL_BASE_URL=${{laravel-app.RAILWAY_PUBLIC_DOMAIN}}
MODEL_SAVE_PATH=models/
LOG_LEVEL=INFO
```

**Deploy:**
```bash
railway up -s ai-service
```

### 5. Run Migrations (2 min)

```bash
railway run -s laravel-app php artisan migrate --force
railway run -s laravel-app php artisan db:seed --class=AdminSeeder
```

---

## ✅ Verification

### Test Health Endpoints

```bash
# Laravel
curl https://your-app.up.railway.app/health

# AI Service  
curl https://your-ai-service.up.railway.app/health
```

### Test AI Integration

```bash
railway run -s laravel-app php artisan ai:test-flask
```

---

## 🔑 Default Admin Credentials

After seeding, use these credentials to login:

```
URL: https://your-app.up.railway.app/admin/dashboard
Email: admin@jru.edu.ph
Password: [Check your AdminSeeder.php]
```

---

## 📊 Service URLs

After deployment, you'll have:

```
Laravel:    https://project-iso-production.up.railway.app
AI Service: https://project-iso-ai-production.up.railway.app
Database:   postgresql://[auto-configured]
```

---

## 🔧 Essential Commands

### View Logs
```bash
railway logs -s laravel-app
railway logs -s ai-service
```

### Run Artisan Commands
```bash
railway run -s laravel-app php artisan [command]
```

### Clear Caches
```bash
railway run -s laravel-app php artisan cache:clear
railway run -s laravel-app php artisan config:clear
```

### Database Commands
```bash
# Check migration status
railway run -s laravel-app php artisan migrate:status

# Rollback migration
railway run -s laravel-app php artisan migrate:rollback

# Fresh migration (⚠️ DESTROYS DATA)
railway run -s laravel-app php artisan migrate:fresh --seed
```

---

## 🐛 Common Issues & Fixes

### Issue: Build fails with "Composer install failed"

**Fix:**
```bash
# Clear build cache in Railway dashboard
Settings → Clear Build Cache → Redeploy
```

### Issue: "No application encryption key"

**Fix:**
```bash
# Generate key locally
php artisan key:generate --show

# Copy output and set as APP_KEY in Railway
```

### Issue: AI Service returns 500 error

**Fix:**
```bash
# Check logs
railway logs -s ai-service

# Increase memory (Railway dashboard)
Settings → Memory: 2GB

# Reduce workers
# Edit railway.json startCommand: --workers 1
```

### Issue: Database connection refused

**Fix:**
- Verify DB_CONNECTION=pgsql (NOT mysql)
- Check database service is running
- Restart Laravel service

---

## 📝 Environment Variables Template

### Copy-Paste Laravel Variables

```env
APP_NAME=ISO 21001 Survey System
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
DB_CONNECTION=pgsql
DB_HOST=${{PGHOST}}
DB_PORT=${{PGPORT}}
DB_DATABASE=${{PGDATABASE}}
DB_USERNAME=${{PGUSER}}
DB_PASSWORD=${{PGPASSWORD}}
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FLASK_AI_SERVICE_URL=https://ai-service.railway.internal
SANCTUM_STATEFUL_DOMAINS=${{RAILWAY_PUBLIC_DOMAIN}}
LOG_LEVEL=error
```

### Copy-Paste AI Service Variables

```env
FLASK_PORT=5000
FLASK_DEBUG=false
FLASK_ENV=production
LARAVEL_BASE_URL=${{laravel-app.RAILWAY_PUBLIC_DOMAIN}}
MODEL_SAVE_PATH=models/
LOG_LEVEL=INFO
MAX_WORKERS=2
```

---

## 🎯 Resource Allocation

### Recommended Settings

| Service | CPU | Memory | Notes |
|---------|-----|--------|-------|
| Laravel | 1-2 vCPU | 512MB-1GB | Scale based on traffic |
| AI Service | 1-2 vCPU | 1-2GB | ML models need more RAM |
| PostgreSQL | 1 vCPU | 256MB-1GB | Depends on data size |

---

## 📚 Full Documentation

For detailed instructions, see:
- [Complete Deployment Guide](./RAILWAY_MONOREPO_DEPLOYMENT_GUIDE.md)
- [Railway Documentation](https://docs.railway.app)

---

## 🆘 Need Help?

- **Railway Discord**: https://discord.gg/railway
- **Railway Support**: https://help.railway.app
- **Check Logs**: `railway logs -s [service-name]`

---

**That's it! Your application should now be live on Railway! 🎉**

