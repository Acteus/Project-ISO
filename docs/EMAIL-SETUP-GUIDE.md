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

## Testing Your Email Setup
**Limits:** 100 emails/day (free tier)

---

## ✅ Option 5: Zoho Mail (Custom Domain)

**Pros:**
- ✅ Works with custom domains (kwadrateam.dev)
- ✅ Reliable SMTP delivery and good deliverability when DNS (SPF/DKIM) configured

**Requirements / Notes:**
1. Zoho Mail account with your domain (kwadrateam.dev) already added and verified in Zoho Admin.
2. If your Zoho account has 2-Factor Authentication enabled (recommended), create an App Password for SMTP usage.
3. Set up SPF and DKIM records in your DNS for kwadrateam.dev to improve deliverability (example below).

**Setup (example `.env`):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@kwadrateam.dev
MAIL_PASSWORD=your-zoho-smtp-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@kwadrateam.dev"
MAIL_FROM_NAME="Kwadrateam"
```

**SMTP details:**
- Host: `smtp.zoho.com`
- Port: `587` (TLS) or `465` (SSL)
- Encryption: `tls` (recommended)
- Username: full Zoho email address (e.g. `no-reply@kwadrateam.dev`)
- Password: Zoho account password or preferably an App Password when 2FA is enabled

**DNS (recommended):**
- SPF TXT (example):
    - `v=spf1 include:zoho.com ~all`
- DKIM: generate DKIM keys in Zoho Mail admin and add the provided TXT record to your DNS.

After adding DNS records, allow propagation time (up to 48 hours) and verify in Zoho Admin.

**Common issues & tips:**
- If emails land in spam, ensure SPF and DKIM are configured for `kwadrateam.dev`.
- Use an App Password if Zoho blocks SMTP with regular account password.
- Test sending to several providers (Gmail, Outlook) to confirm deliverability.

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
