# Railway 500 Error Diagnosis & Fix

## Your Current Error Analysis

Based on your Railway log:
```json
{
  "httpStatus": 500,
  "upstreamAddress": "http://[fd12:c007:1d2f:1:9000:1d:a4f7:98a1]:8080",
  "upstreamRqDuration": 1412,
  "upstreamErrors": ""
}
```

### Key Findings:
1. **Port Mismatch Detected**: Railway is trying to connect to port **8080**
2. **Your app defaults to port 8000** (check `start.sh`)
3. **No upstream errors** means Railway can't even connect to your app

## Critical Issues to Fix

### Issue #1: Port Configuration (MOST LIKELY CAUSE)

Railway automatically sets the `PORT` environment variable. Your app should use it, but there might be a mismatch.

**Fix:** Ensure Railway is passing the PORT variable correctly.

1. In Railway dashboard, check if `PORT` is set in Variables
2. If it's set to 8080, that's fine - your app should detect it
3. If it's NOT set, Railway should auto-inject it

**Verify in start.sh:**
```bash
export PORT=${PORT:-8000}
```
This should pick up Railway's PORT automatically.

### Issue #2: Missing APP_KEY (CRITICAL)

Without APP_KEY, Laravel returns 500 immediately.

**To check:**
1. Go to Railway → Your Service → Variables
2. Look for `APP_KEY`
3. If missing or empty, generate one:

```bash
# Run locally:
php artisan key:generate --show

# Output will be something like:
# base64:abcd1234567890...

# Copy the ENTIRE output (including "base64:" prefix)
# Add to Railway Variables:
APP_KEY=base64:your_generated_key_here
```

### Issue #3: Database Connection Failed

Your app switched from SQLite to MySQL. Check if MySQL is connected.

**To verify:**
1. Railway Dashboard → Your Project
2. Check if you have a MySQL service
3. If NO MySQL service exists:
   - Click "+ New" → "Database" → "Add MySQL"
   - Wait for provisioning (~1-2 minutes)
   - Redeploy your app

**If MySQL exists, verify variables:**
The following should be automatically set by Railway:
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`

## How to View REAL Error Logs

The log you provided is just the HTTP access log. You need the **application logs**:

### In Railway Dashboard:
1. Click on your service
2. Go to "Deployments" tab
3. Click on the latest deployment
4. Scroll through the logs - you should see output from `start.sh`

### What to look for:
```
🚀 Starting Laravel application...
================================================
📋 Environment: production
🐛 Debug Mode: false
📝 Log Channel: stderr

[Database Section]
🔌 Testing database connection...
   Connection: mysql
   Host: monorail.proxy.rlwy.net
   Database: railway
   User: root

✅ Database connection successful   <-- GOOD
❌ Database connection FAILED!      <-- BAD - This is your problem
```

### If you see database connection failed:
Your MySQL isn't connected or credentials are wrong.

### If you see APP_KEY warnings:
```
🚨 CRITICAL ERROR: APP_KEY is not set!
```
You MUST set APP_KEY in Railway variables.

### If logs don't show anything:
The app is crashing before start.sh even runs - likely a build issue.

## Step-by-Step Fix Process

### Step 1: Set APP_KEY (REQUIRED)
```bash
# On your local machine:
php artisan key:generate --show

# Copy output, then in Railway:
# Variables tab → New Variable
# Name: APP_KEY
# Value: base64:your_generated_key_here (paste full output)
```

### Step 2: Add MySQL Database
1. Railway Dashboard → Your Project
2. Click "+ New" → "Database" → "Add MySQL"
3. Wait for it to provision
4. Railway will automatically inject MySQL credentials

### Step 3: Verify PORT Configuration
In Railway Variables, check if PORT is set:
- **If PORT is set to 8080**: That's fine, app will use it
- **If PORT is not set**: Railway should auto-inject it, but you can manually set it to 8080

### Step 4: Force Redeploy
After setting variables:
1. Railway will auto-redeploy
2. Or manually: Settings → Redeploy

### Step 5: Check Deployment Logs
1. Go to Deployments tab
2. Click latest deployment
3. Read the full log output from start.sh
4. Look for any red ❌ or 🚨 errors

## Quick Diagnostic Checklist

Run through this checklist in Railway:

- [ ] **APP_KEY is set** in Variables tab
- [ ] **MySQL service exists** in project
- [ ] **MYSQLHOST exists** in Variables (auto-injected by Railway)
- [ ] **PORT variable** exists (should be auto-set by Railway)
- [ ] **Build completed successfully** (check Deployments → Build Logs)
- [ ] **Start script ran** (check Deployments → Logs for start.sh output)
- [ ] **No database errors** in logs
- [ ] **Server started message** appears in logs: "✅ Server started with PID..."

## Common Error Patterns

### Pattern 1: Blank/Generic 500 with no logs
**Cause:** APP_KEY missing or invalid
**Fix:** Generate and set APP_KEY in Railway variables

### Pattern 2: Database connection errors in logs
**Cause:** MySQL not attached or wrong credentials
**Fix:** Add MySQL database in Railway

### Pattern 3: Port mismatch (your current issue)
**Cause:** Railway routing to wrong port
**Fix:** Ensure PORT env var is properly read by start.sh

### Pattern 4: Build succeeds but app crashes immediately
**Cause:** Missing PHP extensions or dependencies
**Fix:** Check composer.json has all required extensions

## Testing After Fix

### Test 1: Health Check
```bash
curl https://survey.kwadrateam.dev/up
```
**Expected:** `{"status":"ok"}`
**If fails:** Check deployment logs

### Test 2: Home Page
```bash
curl https://survey.kwadrateam.dev/
```
**Expected:** HTML response
**If 500:** Check Laravel logs in Railway deployment logs

### Test 3: Admin Login
```bash
curl -X POST https://survey.kwadrateam.dev/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"Admin@1"}'
```
**Expected:** Token or session response
**If 500:** Database or auth issue

## Emergency Debug Mode

If you're still stuck, temporarily enable debug mode to see the actual error:

1. In Railway Variables, add:
   ```
   APP_DEBUG=true
   ```
2. Redeploy
3. Visit https://survey.kwadrateam.dev in browser
4. You'll see the actual Laravel error page with stack trace
5. **⚠️ CRITICAL:** After diagnosing, SET BACK TO `false` immediately!
   ```
   APP_DEBUG=false
   ```

## Most Likely Root Cause (Based on Your Log)

Given your specific error log, the issue is **one of these three**:

### 1. Missing APP_KEY (80% probability)
- Laravel can't start without APP_KEY
- Returns 500 immediately
- **FIX:** Generate and set APP_KEY in Railway

### 2. Database Connection Failed (15% probability)
- App starts but crashes when trying to use database
- Especially for sessions (which need database)
- **FIX:** Add MySQL service in Railway

### 3. Port Misconfiguration (5% probability)
- Railway expects port 8080 but app serves on different port
- **FIX:** Ensure PORT env var is correctly used in start.sh

## Next Steps

1. **Immediately**: Set APP_KEY in Railway Variables (see Step 1 above)
2. **Then**: Verify MySQL service exists
3. **Finally**: Check deployment logs for specific error messages
4. **Report back** with the actual deployment logs (not HTTP access logs)

## Where to Find the Real Logs

**NOT THIS** (HTTP access log - not useful):
```json
{"httpStatus": 500, "path": "/", ...}
```

**YES THIS** (Application deployment logs):
```
🚀 Starting Laravel application...
📊 Running database migrations...
✅ Server started with PID 1234
```

**To access real logs:**
Railway Dashboard → Your Service → Deployments → Click Latest → Scroll through output

---

**Once you find the real deployment logs, share them and I can give you the exact fix!**

