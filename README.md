# Gayle's Portfolio

A single-page portfolio site built with Laravel 13. It renders one page from Blade views and handles the contact form itself, emailing each inquiry. It needs no database and no front-end build step.

## Getting started

Requires PHP 8.3+ and Composer.

```bash
composer setup      # install, copy .env, generate key
composer dev        # http://127.0.0.1:8000
composer test
```

## Structure

- `config/portfolio.php`: all page content (projects, stages, services, experience, traits, tools). Text in `[square brackets]` renders as a highlighted placeholder.
- `resources/views/home.blade.php`: page sections. Shared pieces live in `resources/views/partials/` (project card, case study, mock illustration, contact link), and the shell (head, header, footer) in `layouts/app.blade.php`.
- `public/css/portfolio.css`, `public/js/portfolio.js`: styles and scripts, served as-is.
- `app/Http/Controllers/InquiryController.php`, `app/Http/Requests/StoreInquiryRequest.php`, `app/Mail/InquiryReceived.php`: the contact form. Submissions go to `POST /inquiries` (rate limited to 5 a minute) and are emailed to `PORTFOLIO_EMAIL`, with reply-to set to the sender.

## Contact form and leads

Every inquiry is saved to the `leads` table first, then emailed to `PORTFOLIO_EMAIL` (reply-to set to the sender). A lead is kept even if the email fails; `notified_at` stays empty so you can spot it.

- **Stored per lead:** name, email, company, service, details, `status` (`new` by default; use `contacted`, `won`, `lost`), first-visit attribution (`utm_source`, `utm_medium`, `utm_campaign`, `referrer`, `landing_page`), IP address and user agent.
- **Spam protection:** Cloudflare Turnstile (checked server-side), a hidden honeypot field that silently drops bots, and a rate limit of 5 submissions a minute per IP.
- **Turnstile** turns on when `TURNSTILE_SITE_KEY` and `TURNSTILE_SECRET_KEY` are set. With them unset (local development) the widget is hidden and the check is skipped.
- **Viewing leads:** `php artisan tinker`, then `App\Models\Lead::latest()->get()`, or connect a database client to the Laravel Cloud database.

## Before launch

1. Set the contact details: `PORTFOLIO_EMAIL`, `PORTFOLIO_LINKEDIN`, `PORTFOLIO_INSTAGRAM`, `PORTFOLIO_LOCATION`, `PORTFOLIO_RESPONSE_TIME`. Any left unset show as placeholders.
2. Configure Resend (see Deploying). Locally the default `log` mailer writes inquiries to `storage/logs/laravel.log`.
3. Replace the remaining placeholders in `config/portfolio.php` and the views: screenshots, case study challenges, and verified results only. A project can show results by adding a `results` key.

## Deploying (Laravel Cloud)

1. **Database:** attach the MySQL database to the environment. Cloud injects the `DB_*` variables itself, so don't put credentials in the repo.
2. **Deploy commands:** make sure they include `php artisan migrate --force`, which creates the `leads` table (and the sessions and cache tables).
3. **Environment variables:**
   - `SESSION_DRIVER=database` and `CACHE_STORE=database`. File sessions aren't shared between instances and can cause "page expired" (419) errors on the form; the rate limiter also needs a shared cache.
   - `TURNSTILE_SITE_KEY`, `TURNSTILE_SECRET_KEY`.
   - `PORTFOLIO_EMAIL`, plus mail through Resend: `MAIL_MAILER=resend`, `RESEND_API_KEY`, `MAIL_FROM_ADDRESS` and `MAIL_FROM_NAME`. The from address must be on a domain verified in Resend. Without a domain, `onboarding@resend.dev` works, but Resend then only delivers to the email address that owns the Resend account.
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://itsmegayleee.laravel.cloud` (or the custom domain).
4. **Turnstile hostnames:** in the Cloudflare dashboard, add every domain the site is served from (the `laravel.cloud` address and any custom domain) to the widget's hostname list, or the widget won't load.
5. After deploying, refresh link previews in the Facebook Sharing Debugger or LinkedIn Post Inspector.
