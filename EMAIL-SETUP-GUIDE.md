# Local Email Setup Guide
**Cross-Platform Solutions (Windows & macOS)**

## ✅ Option 1: Gmail SMTP (RECOMMENDED - Currently Active)

**Pros:**
- ✅ Sends to ANY email address
- ✅ Free for development
- ✅ Works on Windows & macOS
- ✅ Reliable and fast
- ✅ No installation needed

**Setup:**
1. Enable 2-Factor Authentication on your Gmail
2. Generate App Password: https://myaccount.google.com/apppasswords
3. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-gmail@gmail.com"
MAIL_FROM_NAME="Your Name"
```

**Limits:** 500 emails/day (plenty for development)

---

## Option 2: Mailtrap (Free Tier)

**Pros:**
- ✅ Sends to real emails (with free tier)
- ✅ Great for testing
- ✅ Email analytics and debugging
- ✅ Works on Windows & macOS

**Setup:**
1. Sign up: https://mailtrap.io
2. Get SMTP credentials from dashboard
3. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-verified-email@domain.com"
MAIL_FROM_NAME="Your Name"
```

**Limits:** 1,000 emails/month (free tier)

---

## Option 3: Outlook/Microsoft 365 SMTP

**Pros:**
- ✅ Sends to ANY email address
- ✅ Free with Outlook account
- ✅ Works on Windows & macOS

**Setup:**
1. Use your Outlook/Hotmail/Microsoft 365 account
2. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@outlook.com"
MAIL_FROM_NAME="Your Name"
```

**Note:** May require App Password if 2FA is enabled

---

## Option 4: SendGrid (Free Tier)

**Pros:**
- ✅ 100 emails/day free forever
- ✅ Professional email delivery
- ✅ Works on Windows & macOS
- ✅ Great analytics

**Setup:**
1. Sign up: https://sendgrid.com
2. Create API Key
3. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-verified-email@domain.com"
MAIL_FROM_NAME="Your Name"
```

**Limits:** 100 emails/day (free tier)

---

## Testing Your Email Setup

Run this Laravel artisan command to test:
```bash
php artisan tinker
```

Then in tinker:
```php
Mail::raw('Test email from local environment!', function($message) {
    $message->to('your-test-email@gmail.com')
            ->subject('Test Email');
});
```

Check for errors and verify the email arrives!

---

## Current Configuration

Your `.env` is currently configured for **Gmail SMTP**.

**Next Steps:**
1. Replace `your-gmail@gmail.com` with your actual Gmail address
2. Generate an App Password from Google
3. Replace `your-16-char-app-password` with the generated password
4. Test sending an email!

---

## MailHog (Already Installed)

You have MailHog installed for **local testing only** (doesn't send real emails):

**To use MailHog for local testing:**
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

**Run MailHog:**
```bash
mailhog
```

**View emails:** http://localhost:8025

This is great for testing email templates without sending real emails!

---

## Switching Between Configurations

You can create multiple `.env` files:
- `.env` - Your main config
- `.env.local` - MailHog for local testing
- `.env.gmail` - Gmail SMTP for real emails

Switch by copying: `cp .env.gmail .env`
