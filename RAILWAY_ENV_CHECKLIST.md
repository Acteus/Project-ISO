# Railway Environment Variables Checklist for Fixing 500 Error

## Critical Variables (MUST SET)

### 1. APP_KEY (MOST CRITICAL)
```bash
# Generate locally first:
php artisan key:generate --show

# Then set in Railway Variables tab:
APP_KEY=base64:your_generated_key_here
```
**⚠️ Without APP_KEY, Laravel will return 500 error!**

### 2. Database Connection (Railway MySQL)
If you added a MySQL database in Railway, these should be auto-injected:
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`

The `start.sh` script will automatically map these to Laravel's `DB_*` variables.

**Verify MySQL is attached:**
1. Go to your Railway project
2. Check if MySQL service exists
3. If not, click "+ New" → "Database" → "Add MySQL"
4. The environment variables will be automatically injected

### 3. Application Settings
```bash
APP_NAME="ISO 21001 Quality Education System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
```

## Optional But Recommended

### Session Configuration
```bash
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_HTTP_ONLY=true
```

### Admin Account (for db:seed)
```bash
ADMIN_NAME="System Administrator"
ADMIN_USERNAME=admin
ADMIN_EMAIL=admin@yourdomain.com
ADMIN_PASSWORD=YourSecurePassword123!
```

### Logging
```bash
LOG_CHANNEL=stderr
LOG_LEVEL=info
```

### Cache & Queue
```bash
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## How to Set Environment Variables in Railway

1. Open your Railway project
2. Click on your service
3. Go to the "Variables" tab
4. Click "New Variable"
5. Add each variable one by one
6. Deploy will automatically restart after saving

## Troubleshooting Steps

### Step 1: Check Railway Logs
```bash
# In Railway dashboard:
# Click your service → "Deployments" → Click latest deployment → View logs
```

Look for errors like:
- "No application encryption key has been specified"
- "SQLSTATE[HY000]" (database connection errors)
- "Session store not configured correctly"

### Step 2: Verify Database Connection
In Railway logs, you should see from `start.sh`:
```
✅ Using MySQL database from Railway
✅ Database connection successful
```

If you see:
```
⚠️ Database connection failed
```
Then your MySQL service isn't properly connected.

### Step 3: Run Migrations Manually
If migrations aren't running, you can force them:
1. In Railway, go to your service
2. Click "Settings" → "Deploy Command"
3. Temporarily change to: `bash -c "php artisan migrate --force && bash start.sh"`
4. Deploy
5. Change it back to: `bash start.sh`

### Step 4: Check Health Endpoint
After deployment, test:
```bash
curl https://your-app.railway.app/up
```

Should return: `{"status":"ok"}`

### Step 5: Test Admin Login
```bash
curl -X POST https://your-app.railway.app/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"Admin@1"}'
```

## Common Fixes for 500 Error

### Fix 1: Missing APP_KEY
```bash
# Generate locally:
php artisan key:generate --show

# Output example: base64:abcd1234...
# Copy this entire string including "base64:" prefix
# Set in Railway Variables tab as APP_KEY
```

### Fix 2: Database Not Connected
1. Add MySQL in Railway (+ New → Database → MySQL)
2. Wait for it to provision (~1 minute)
3. Redeploy your app

### Fix 3: Sessions Table Missing
Check if migrations ran:
```sql
-- In Railway MySQL dashboard, run:
SHOW TABLES;

-- You should see:
-- migrations, sessions, admins, survey_responses, etc.
```

If tables are missing, migrations didn't run. Force run them (see Step 3 above).

### Fix 4: Permission Issues
The `start.sh` creates necessary directories, but if you still get permission errors:
```bash
# Add to start.sh before migrations:
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Quick Diagnostic Commands

Run these in Railway's terminal (click service → Settings → Terminal):

```bash
# Check PHP version
php -v

# Check database connection
php artisan db:show

# Check migrations status
php artisan migrate:status

# Test configuration
php artisan config:show database

# Check if sessions table exists
php artisan tinker
>>> DB::table('sessions')->count();
```

## Expected Railway Build Logs (Success)

```
🚀 Starting Laravel application...
✅ Using MySQL database from Railway
🔌 Testing database connection...
✅ Database connection successful
📊 Running database migrations...
  [Migration] Migrating: 0001_01_01_000003_create_sessions_table
  [Migration] Migrated:  0001_01_01_000003_create_sessions_table (45.67ms)
🌐 Starting PHP server on port 8000...
✅ Server started with PID 1234
📊 Running database seeder in background...
  [Seeder] Admin account ensured.
  [Seeder] Username: admin
  [Seeder] Email: admin@example.com
```

## Still Getting 500 Error?

1. **Check Railway logs for specific error message**
2. **Enable debug mode temporarily:**
   ```bash
   APP_DEBUG=true
   ```
   Deploy and visit your app to see the actual error
   **⚠️ REMEMBER TO SET BACK TO `false` AFTER DEBUGGING**

3. **Check Laravel logs in Railway:**
   ```bash
   # The logs go to stderr, visible in Railway deployment logs
   ```

4. **Verify all critical variables are set:**
   - APP_KEY ✓
   - MYSQLHOST (or DB_HOST) ✓
   - MYSQLDATABASE (or DB_DATABASE) ✓
   - MYSQLUSER (or DB_USERNAME) ✓
   - MYSQLPASSWORD (or DB_PASSWORD) ✓

5. **Nuclear option - Fresh deployment:**
   ```bash
   # In Railway Variables, add:
   FORCE_MIGRATE=true
   
   # This will clear all caches and force migrations
   # Then remove FORCE_MIGRATE after successful deployment
   ```

