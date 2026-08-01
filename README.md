# Sathira Sri Sathsara — Portfolio & Blog

A production-oriented, server-rendered personal portfolio and Markdown publishing system built with PHP 8.2, MySQL, PDO, League CommonMark, and HTMLPurifier. It includes a responsive three-column public site, searchable blog, secure admin editor, scheduled publishing, technical SEO, RSS, sitemap, and machine-readable discovery files.

[![Screenshot](https://res.cloudinary.com/dhqcnszvn/image/upload/v1785597559/Screenshot_2026-08-01_204905_wogyfz.png)](https://res.cloudinary.com/dhqcnszvn/image/upload/v1785597559/Screenshot_2026-08-01_204905_wogyfz.png)


## Requirements

- PHP 8.2+ with PDO MySQL, mbstring, fileinfo, iconv, JSON, DOM, and GD (recommended for media)
- MySQL 8.0+ or compatible MariaDB
- Composer 2
- Apache with `mod_rewrite`, or Nginx with PHP-FPM

## Local installation

```bash
composer install
cp .env.example .env
# Edit .env: set APP_KEY, database values, and ADMIN_* values
mysql -u root -p -e "CREATE DATABASE portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
composer migrate
composer seed
php -S localhost:8000 -t public public/index.php
```

On PowerShell use `Copy-Item .env.example .env`. Open `http://localhost:8000`; the admin login is at `/admin/login`. The admin password must be at least 12 characters. Never commit `.env`.

## Architecture

`public/index.php` is the only web entry point. The router maps clean URLs to small controllers. Controllers coordinate repositories and services; repositories own prepared SQL; templates only present escaped data. Markdown is stored in `posts.markdown_content`, converted with League CommonMark, purified with HTMLPurifier, and cached in `rendered_html_cache` whenever an editor saves. Public visibility always requires `status = published`, a non-future `published_at`, and no soft-deletion timestamp.

Key directories:

- `app/Core`: application, router, database, view, and CSRF foundations
- `app/Controllers`: public, authentication, and administration endpoints
- `app/Repositories`: data access and paginated queries
- `app/Services`: authentication, Markdown rendering, and slugs
- `app/Validation`: centralized content and upload validation
- `app/Views`: layouts, components, public pages, admin pages, and errors
- `database/migrations`: versioned schema
- `public`: document root and immutable assets
- `storage`: sessions, logs, and cache
- `tests`: PHPUnit unit/security regression tests

## Content workflow

Sign in, choose **Posts → New post**, add the title, excerpt, Markdown, category, publishing state, schedule, and SEO fields, then save. The slug is generated in the browser but normalized again server-side. Publishing immediately sets `published_at`; scheduled posts become visible after the scheduler runs. Editing regenerates sanitized HTML and invalidates public page cache files.

Supported Markdown includes headings, emphasis, lists, task lists, links, images, fenced code, inline code, blockquotes, tables, strikethrough, autolinks, and rules. Raw HTML is stripped. External links receive `noopener noreferrer`; article images are lazy-loaded; tables scroll within their container; code blocks get a lightweight copy button.

## Commands

```bash
composer migrate       # apply new SQL migrations once
composer seed          # create/update the environment-defined administrator
composer schedule      # publish due posts and clean expired reset tokens
composer test          # run PHPUnit
composer dump-autoload --optimize
```

Cron example (every minute):

```cron
* * * * * cd /var/www/portfolio && /usr/bin/php bin/console schedule >> storage/logs/scheduler.log 2>&1
```

## Production deployment

1. Create a database and a least-privilege database user.
2. Upload the repository to `/var/www/portfolio`; keep `.env`, `app`, `config`, `database`, `storage`, and `vendor` outside the public document root.
3. Run `composer install --no-dev --classmap-authoritative`.
4. Copy `.env.example` to `.env`; set `APP_ENV=production`, `APP_DEBUG=false`, the HTTPS `APP_URL`, a random 64+ character `APP_KEY`, database values, admin seed values, and `SESSION_SECURE=true`.
5. Run `php bin/console migrate` and `php bin/console seed`, then remove `ADMIN_PASSWORD` from the runtime environment if reseeding is not needed.
6. Give the PHP-FPM user write access only to `storage/` and `public/uploads/`, for example `chown -R www-data:www-data storage public/uploads && chmod -R 750 storage public/uploads`.
7. Use `deploy/nginx.conf.example` or `deploy/apache-vhost.conf.example`, changing the host and path. Point the document root to `/var/www/portfolio/public`.
8. Enable HTTPS, HSTS at the proxy after HTTPS is verified, PHP OPcache, Brotli/Gzip, and the scheduler cron entry. Reload the web server.
9. Verify `/health`, `/robots.txt`, `/sitemap.xml`, `/rss.xml`, and `/llms.txt`; then validate structured data and social previews.

Back up the database and uploaded media together. Encrypt off-site backups, test restores, retain multiple generations, and exclude `.env` from ordinary source archives. Cache files and sessions do not require backup.

## Security notes

All state changes require CSRF tokens. Login rotates the session ID, uses generic errors, records hashed IPs, and limits five failures per email/IP per 15 minutes. Passwords use Argon2id when available. PDO emulated prepares are disabled. Markdown is parsed in safe mode and purified. Security headers include CSP, frame denial, MIME sniffing protection, referrer policy, and permissions policy. Upload validation rejects SVG and executable formats by default. Production errors are generic and logged by PHP; configure `error_log` to `storage/logs/php-error.log`.

## SEO and discoverability

Pages include unique titles/descriptions, canonical URLs, Open Graph/Twitter metadata, semantic article dates, JSON-LD (`ProfilePage`/`Person` and `BlogPosting`), breadcrumbs, clean slugs, XML sitemap, RSS, robots.txt, and llms.txt. These improve machine readability but do not guarantee rankings or AI citations. Lighthouse targets must be measured on the final hosting environment.

## Manual QA checklist

- Check desktop three-column, tablet two-column, and mobile single-column layouts at common widths.
- Navigate every control by keyboard; verify visible focus, skip link, labels, and 44px touch targets.
- Test five failed logins, then a successful login after the rate window.
- Create, edit, preview, publish, schedule, archive, and soft-delete a post; confirm drafts stay private.
- Test Markdown tables, code, task lists, malicious scripts, images, external links, and copy buttons.
- Search titles, excerpts, body text, categories, and tags; test empty and multi-page results.
- Verify 404 and expired-CSRF responses and production-safe 500 output.
- Validate sitemap, RSS, canonical, Open Graph, robots, JSON-LD, and llms.txt.
- Confirm cache invalidation after content/settings changes and scheduler execution.
- Test JPEG, PNG, WebP, oversized, renamed executable, and SVG upload validation before enabling media UI.

## Troubleshooting

- **Database connection fails:** confirm PDO MySQL is installed, `.env` values are correct, and the DB user has access.
- **Clean URLs return 404:** enable Apache `mod_rewrite`/`AllowOverride All`, or verify Nginx `try_files`.
- **HTMLPurifier cache errors:** grant the PHP user write access to `storage/cache`.
- **Sessions do not persist:** grant write access to `storage/sessions` and verify cookie/HTTPS settings.
- **Scheduled posts remain hidden:** install the cron entry and check the server timezone.

## Current scope

The schema supports media, tags, password reset tokens, audit logs, and richer social metadata. The primary production flows delivered are secure login, profile/settings management, post create/edit/publish/schedule/delete, public browsing, search, feeds, and SEO. Media-library UI, category/tag CRUD UI, password-reset email delivery, post duplication, and CV upload/download require a configured mail/storage policy and are intentionally not exposed as incomplete endpoints.
