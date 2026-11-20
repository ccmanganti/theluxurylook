# The Luxury Look - Laravel + Filament Website

A portfolio-first, design-first and service-first website rebuild for **The Luxury Look** in Scottsdale, Arizona.

**Status: DEVELOPMENT / MIGRATION PREVIEW ONLY. Not ready to replace Webflow yet.**

## What is included

- Laravel 12 application source, with Filament 5 admin panel at `/admin`.
- Editorial luxury website with responsive Home, Services, Studio, About, Contact, Journal and portfolio sections.
- Six **exact** existing portfolio category URL slugs, including the original misspelling in `corporate-brandding-projects-the-luxury-look`.
- 54 original/linked portfolio project records; cover images currently reference the company-owned Webflow CDN as **temporary migration sources**.
- Original `sitemap.xml` inventory of 96 URLs, retained in `database/data/legacy-sitemap.json`.
- Additional discovered legacy links not present in XML (Nichols Project and November 2026 seasonal blog).
- Filament CRUD for Projects, Portfolio Categories, Services, Blog Articles, Pages, Legacy URL Audit and Client Inquiries. Each portfolio/service entity has a content verification toggle.
- Canonical/OG metadata, dynamic `/robots.txt` and `/sitemap.xml`, redirect/410 handling, preview-mode noindex, email-ready lead form.
- URL audit command and feature tests. **No original URL is automatically approved for production**.

## Prerequisites

PHP 8.2+ with extensions needed by Laravel / Filament (`mbstring`, `ctype`, `openssl`, `pdo_sqlite` or `pdo_mysql`, `fileinfo`, `tokenizer`, `xml`, `dom`, `gd` or `imagick` for image uploads), Composer 2, a suitable database, and a PHP web server that uses `public/` as its web root. Filament 5 also uses Livewire / Alpine / Tailwind internally (installed through Composer dependencies and published assets).

## Local setup

```bash
cp .env.example .env
composer install
php artisan key:generate
# For SQLite:
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan filament:assets
php artisan make:filament-user
```

After creating your Filament user, grant admin access deliberately:

```bash
php artisan tinker
# Then, in Tinker:
# App\Models\User::where('email', 'YOUR_EMAIL')->update(['is_admin' => true]);
```

Run the website using `php artisan serve` and visit http://localhost:8000; visit `/admin` to edit content.

**Do not set `SITE_LAUNCH_READY=true` yet.** Preview mode sets global `X-Robots-Tag: noindex, nofollow, noarchive`, emits a disallow-all `robots.txt`, and adds a visible migration banner.

## Code notes

- For development, content under migration is displayed as editorial placeholders at the **original routes**, keeping URL mapping testable. Do **not** deploy incomplete placeholders to the live domain.
- The actual project galleries and full original blog article bodies are not yet migrated; those records are explicitly marked `content_verified=false`.
- The contact form stores submissions in the database. Set an SMTP driver in `.env` for email delivery; `MAIL_MAILER=log` is the safe local default. Validate real email delivery before launch.
- Payments and shopping URLs are reserved but not functional. The site responds with `503` on those route shells rather than pretending to process payments.
- Upload original high-resolution photos to Filament's `cover_upload` and `gallery_uploads` fields. **Do not cancel Webflow until all CDN assets are owned and tested locally.**
- For existing slugs, avoid edits; use the `Legacy URL Audit` admin panel to explicitly approve changes. `redirect` status accepts only local absolute paths and sends HTTP 301. `retired` sends HTTP 410. Audit both before using.

## Pre-launch audit

```bash
php artisan migration:audit
php artisan test
```

`migration:audit` exits **nonzero** when any of the original 96 URLs is still awaiting content decisions. This is an intentional release blocker.

See `docs/SEO_MIGRATION_PLAN.md` for the SEO preservation plan, detailed launch checks, and limitations. GitHub remote is not included, because the connected GitHub account had no The Luxury Look repository at the time this scaffold was prepared.

## Build verification

Before Composer setup, run `python3 scripts/check_manifest.py` to verify the exact 96 legacy paths, the six category slugs, unique mapped photography references and linked case studies. On a fully provisioned PHP environment, run `composer install && php artisan test && php artisan migration:audit`. The included `.github/workflows/ci.yml` runs the offline manifest check and Laravel tests on GitHub pushes and pull requests.

**Starter validation scope:** This source distribution has passed PHP syntax checks and the offline URL manifest integrity check. PHP dependencies were not installed in the preparation environment, so full Laravel/Filament runtime and CI checks remain to be completed on a Composer-enabled machine.
