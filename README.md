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

## Before launch

1. Set the contact details in `.env`: `PORTFOLIO_EMAIL`, `PORTFOLIO_LINKEDIN`, `PORTFOLIO_INSTAGRAM`, `PORTFOLIO_LOCATION`, `PORTFOLIO_RESPONSE_TIME`. Any left unset show as placeholders. Until `PORTFOLIO_EMAIL` is set, the form shows a "not connected yet" notice.
2. Configure a real mailer (`MAIL_MAILER`, `MAIL_HOST`, credentials, `MAIL_FROM_ADDRESS`). The default `log` mailer writes inquiries to `storage/logs/laravel.log`.
3. Replace the remaining placeholders in `config/portfolio.php` and the views: portrait, screenshots, case study challenges, and verified results only. A project can show results by adding a `results` key.
4. Set `APP_ENV=production`, `APP_DEBUG=false`, and `APP_URL`.

## Deploying

This is now a PHP app, so GitHub Pages can no longer host it. Use any PHP host (Laravel Cloud, Forge, or shared hosting) with the web root pointed at `public/`, then run `composer install --no-dev --optimize-autoloader` and `php artisan config:cache`.
