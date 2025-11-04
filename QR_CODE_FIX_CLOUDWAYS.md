# QR Code Display Fix for Cloudways

## Problem
QR code images showing "Image Load Error" with path like:
```
File path: qr-codes/2025/11/test-qr-for-dev-use_2025-11-03_22-44-06_kMJQ8Z.png
URL: http://your-domain/storage/qr-codes/2025/11/test-qr-for-dev-use_2025-11-03_22-44-06_kMJQ8Z.png
```

## Root Cause
The storage symbolic link is not created on the Cloudways server, which means Laravel cannot serve files from `storage/app/public` through the `public/storage` URL.

## Solution

### Step 1: SSH into Cloudways Server

```bash
# Get SSH credentials from Cloudways Dashboard → Server → Master Credentials
ssh [your-ssh-user]@[your-server-ip]

# Navigate to your application directory
cd /home/1543265.cloudwaysapps.com/kxvekkgpkz/public_html
```

### Step 2: Fix APP_URL in .env File

**CRITICAL:** This is the most important step!

```bash
# Edit the .env file
nano .env

# Find this line:
# APP_URL=http://localhost

# Change it to your actual Cloudways domain:
# APP_URL=https://app.kwadrateam.dev
# (Replace with your actual domain)

# Save: Ctrl+X, then Y, then Enter
```

### Step 3: Create Storage Link

```bash
# Create the symbolic link
php artisan storage:link
```

You should see:
```
The [public/storage] link has been connected to [storage/app/public].
The links have been created.
```

### Step 3: Verify Storage Link

```bash
# Check if the link was created
ls -la public/ | grep storage
```

You should see something like:
```
lrwxr-xr-x 1 user user 39 Nov 3 22:00 storage -> /path/to/storage/app/public
```

### Step 4: Run Diagnostics

```bash
# Run the storage diagnostics command
php artisan storage:diagnose
```

This will:
- ✓ Check if storage symlink exists
- ✓ Verify configuration
- ✓ Check all QR code files
- ✓ Offer to regenerate missing files if any

### Step 5: Set Correct Permissions

```bash
# Make sure the storage directories have correct permissions
chmod -R 755 storage/app/public
chmod -R 755 public/storage

# Make sure the web server can access them
chown -R www-data:www-data storage/app/public
chown -R www-data:www-data public/storage
```

**Note:** On Cloudways, the web server user might be different. Check with:
```bash
ps aux | grep -E 'apache|nginx' | grep -v root | head -1 | awk '{print $1}'
```

### Step 6: Clear Caches and Reload Configuration

```bash
# Clear all Laravel caches (this will reload the new APP_URL)
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Recache for production
php artisan config:cache
php artisan route:cache

# Verify the APP_URL is correct now
php artisan tinker --execute="echo 'APP_URL: ' . config('app.url') . PHP_EOL;"
```

You should see your actual domain, not `http://localhost`!

### Step 7: Test

1. Visit your admin dashboard
2. Go to QR Codes section
3. View a QR code
4. The image should now display correctly

## If Files Are Missing

If the diagnostics show missing QR code files, you have two options:

### Option A: Regenerate from Diagnostics Command

```bash
# Run diagnostics and select "yes" when asked to regenerate
php artisan storage:diagnose
```

### Option B: Regenerate Individual QR Code

```bash
# Open tinker
php artisan tinker

# Regenerate specific QR code (replace ID with actual ID)
$qr = App\Models\QrCode::find(3);
app(App\Services\QrCodeService::class)->regenerateFile($qr);
exit
```

### Option C: Regenerate All QR Codes

Create a script or run this in tinker:

```bash
php artisan tinker --execute="
\$qrCodeService = app(App\Services\QrCodeService::class);
App\Models\QrCode::chunk(10, function(\$qrCodes) use (\$qrCodeService) {
    foreach (\$qrCodes as \$qrCode) {
        try {
            \$qrCodeService->regenerateFile(\$qrCode);
            echo 'Regenerated QR code #' . \$qrCode->id . PHP_EOL;
        } catch (Exception \$e) {
            echo 'Failed to regenerate QR code #' . \$qrCode->id . ': ' . \$e->getMessage() . PHP_EOL;
        }
    }
});
echo 'Done!' . PHP_EOL;
"
```

## Troubleshooting

### Error: "The [public/storage] link already exists"

The link exists but might be broken. Remove and recreate:

```bash
# Remove the old link
rm public/storage

# Create new link
php artisan storage:link
```

### Error: "Permission denied"

You need proper permissions:

```bash
# Make storage writable
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# If you're running as root (not recommended), change ownership
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### Images Still Not Loading

1. **Check .env file:**
   Make sure `APP_URL` is set correctly:
   ```
   APP_URL=https://your-actual-domain.com
   ```

2. **Clear browser cache:**
   Hard refresh with `Ctrl+F5` (Windows) or `Cmd+Shift+R` (Mac)

3. **Check web server configuration:**
   Make sure your web server (Apache/Nginx) is configured to serve static files from the `public` directory.

4. **Verify file exists:**
   ```bash
   # Check if the actual file exists
   ls -la storage/app/public/qr-codes/2025/10/
   ```

## For Future Deployments

Add this to your deployment script or Git deployment hooks in Cloudways:

```bash
#!/bin/bash

# Navigate to application directory
cd /home/1543265.cloudwaysapps.com/kxvekkgpkz/public_html

# Install dependencies
composer install --no-dev --optimize-autoloader

# Create storage link (if it doesn't exist)
php artisan storage:link

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "Deployment complete!"
```

## Quick Reference

| Command | Purpose |
|---------|---------|
| `php artisan storage:link` | Create storage symlink |
| `php artisan storage:diagnose` | Run full diagnostics |
| `ls -la public/ \| grep storage` | Check if symlink exists |
| `chmod -R 755 storage/` | Fix permissions |
| `php artisan cache:clear` | Clear application cache |

## Still Having Issues?

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs in Cloudways dashboard
3. Run diagnostics: `php artisan storage:diagnose`
4. Verify `.env` settings are correct
5. Contact Cloudways support if file permissions can't be changed

---

**Created:** November 3, 2025  
**Status:** Ready for deployment
