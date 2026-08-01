# AGENTS.md

## Purpose

This repository is Sathira Sri Sathsara's server-rendered PHP portfolio and Markdown blog. Preserve fast public rendering, the dark responsive visual language, secure content administration, and MySQL as the production content source.

## Architecture and conventions

- Require PHP 8.2+, `declare(strict_types=1);`, PSR-12, typed properties, return types, and PSR-4 under `App\\`.
- Keep `public/` as the web root. Route all dynamic requests through `public/index.php`.
- Controllers coordinate requests; repositories own SQL; services own reusable domain behavior; validation belongs in `app/Validation`; templates contain no SQL or business logic.
- Inject dependencies through constructors where useful. Avoid globals, service locators, god classes, framework-sized abstractions, and heavy client dependencies.
- Use prepared PDO statements exclusively. Use transactions for multi-table writes. Schema changes require a new numbered migration; never edit a migration already deployed.
- Public post queries must enforce published status, non-future `published_at`, and `deleted_at IS NULL`.
- Escape template output with `e()`. Only emit trusted, purified Markdown HTML without escaping.
- All state-changing routes require CSRF. Protected routes require admin middleware. Never weaken session rotation, cookie flags, rate limiting, generic auth errors, audit logging, CSP, or upload validation.
- Never store secrets in PHP, templates, migrations, README, tests, or client assets. Secrets belong only in `.env`/runtime configuration. Never log passwords, tokens, cookies, session IDs, or database credentials.
- Preserve the Markdown source as authoritative; regenerate and purify cached HTML after every content change.
- Use the CSS custom properties in `public/assets/css/app.css`. Add tokens rather than repeating colors, spacing, radii, or shadows. Keep controls keyboard accessible and touch targets at least 44px.
- Prefer reusable view components in `app/Views/components`. Preserve one H1, semantic landmarks, server-rendered article content, clean canonical URLs, and page-specific metadata.
- Do not introduce React, Vue, Angular, jQuery, Bootstrap, Tailwind, a full-stack framework, external fonts needed for first paint, or client-side article loading.

## Working commands

```bash
composer install
composer migrate
composer seed
composer schedule
composer test
php -S localhost:8000 -t public public/index.php
```

## Testing expectations

Add or update PHPUnit coverage for routing, visibility rules, authentication, CSRF, validation, Markdown/XSS, pagination, feeds, SEO/canonical behavior, migrations, and uploads whenever related code changes. Run `composer test` plus `php -l` over changed PHP files. For UI work, manually check desktop, tablet, mobile, keyboard navigation, reduced motion, overflow, and production error behavior. Update README and this file when commands, architecture, deployment, or security assumptions change.
