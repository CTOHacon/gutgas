# Gutgas

E-commerce storefront and admin panel for [gutgas.eu](https://gutgas.eu) — a Laravel 10 + Inertia.js + Vue 3 (TypeScript) application with server-side rendering, SEO-driven routing, a custom admin CMS, and a containerized production deploy.

## Stack

| Layer | Technology |
| --- | --- |
| Backend | Laravel 10, PHP 8.1+ (PHP 8.3 in production) |
| Frontend | Vue 3 + TypeScript, Inertia.js, Vite 4 |
| SSR | Inertia SSR (`@vue/server-renderer`), runs on `127.0.0.1:13714` |
| Styling | Tailwind CSS 3, Bootstrap 5, SCSS |
| Database | MySQL 8 |
| Sessions / cache / queue | File-based (no queue worker; `QUEUE_CONNECTION=sync`) |
| Container | Multi-stage Docker — php-fpm + nginx + Inertia SSR via supervisor |

### Notable packages

- **spatie/laravel-permission** — role/permission gating on admin routes
- **spatie/laravel-translatable** — multilingual model fields
- **spatie/laravel-sluggable**, **spatie/laravel-sitemap** — slugs and sitemap generation
- **staudenmeir/laravel-adjacency-list** — nested category trees
- **diglactic/laravel-breadcrumbs** — breadcrumb trails
- **intervention/image** — media processing / scaled images
- **tightenco/ziggy** — Laravel routes in JS
- **zoha/laravel-meta** — entity metadata

## Features

- **Storefront** — product catalog, product pages, product groups, contact pages, checkout, and thank-you flow.
- **SEO-driven routing** — a catch-all `SeoEndpoint` resolves arbitrary URLs to `SeoEntity` records (categories, products, pages), with pagination support and locale-prefixed URLs.
- **Multilingual** — locales `en`, `ru`, `ua` (production default `uk`); URL- and session-based locale handling middleware.
- **Admin CMS** — `/admin` panel (permission-gated) for products, categories, attributes & attribute groups, product filters, product pages, SEO entities, media library, users, and global settings.
- **Order notifications** — new orders and contact/call-back requests are pushed via **Telegram** and **email** (see `OrderController` / `MailController`).
- **Security** — absolute session timeout middleware ([app/Http/Middleware/AbsoluteSessionTimeout.php](app/Http/Middleware/AbsoluteSessionTimeout.php)), login throttling, authentication audit logging to a dedicated `auth` log channel, MIME-verified media uploads with SVG sanitization.

## Project layout

```
app/
  Http/Controllers/        # storefront controllers (Checkout, Order, Mail, SeoEndpoint, ...)
  Http/Controllers/Admin/  # admin CMS controllers
  Http/Middleware/         # locale, SEO redirect, absolute session timeout, ...
  Models/                  # Product, Category, Attribute, Order, Page, MediaFile, SeoEntity, ...
resources/js/
  Pages/                   # Inertia page components (storefront + Admin/ + Auth/)
  Components/ AdminComponents/ Layouts/ composables/ modules/ api/
routes/
  web.php admin.php auth.php ajax.php api.php breadcrumbs.php
docker/                    # nginx.conf, php.ini, supervisor, entrypoint.sh
lang/                      # en, ru, ua translations
```

## Local development

Requires PHP 8.1+, Composer, Node 20+, and a MySQL database.

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
# set DB_* in .env, then:
php artisan migrate

# two terminals:
npm run dev          # Vite dev server (HMR)
php artisan serve    # http://127.0.0.1:8000
```

### Build for production assets

```bash
npm run build        # vue-tsc + vite build + vite build --ssr
```

This type-checks (`vue-tsc`), builds the client bundle to `public/build`, and builds the SSR bundle to `bootstrap/ssr`.

## Production deployment

Deployment is automated via GitHub Actions ([.github/workflows/deploy.yml](.github/workflows/deploy.yml)):

1. **Build** — on push to `main`, a multi-stage Docker image is built and pushed to `ghcr.io/ctohacon/gutgas` (tagged with the commit SHA and `latest`).
2. **Deploy** — over SSH to the production host (rootless Docker): pull the new image, `docker compose up -d`, run `php artisan migrate --force`, then regenerate the sitemap (`php artisan sitemap:generate`).

The app is served behind a CloudPanel reverse proxy at `gutgas.eu`, mapping loopback `127.0.0.1:8091 → 8080` in the container.

### Running the prod stack manually

```bash
cp .env.production.example .env   # fill in APP_KEY, DB_*, MAIL_*, TELEGRAM_*
docker compose -f docker-compose.prod.yaml run --rm app php artisan key:generate --show
docker compose -f docker-compose.prod.yaml up -d
```

The container runs php-fpm, nginx, and the Inertia SSR process under supervisor. Uploads persist in the `app_storage` volume (`storage/app`); MySQL data in `mysql_data`.

### Required production env

See [.env.production.example](.env.production.example). Key variables beyond the Laravel defaults:

- `REGISTRY`, `TAG`, `APP_PORT` — image source and host port
- `APP_LOCALE` / `APP_FALLBACK_LOCALE` — default `uk`
- `DB_*` — MySQL container credentials
- `MAIL_*` — SMTP for order/contact mail
- `TELEGRAM_BOT_TOKEN`, `TELEGRAM_CHAT_ID` — order/contact Telegram notifications
- `ADMIN_EMAIL` — recipient for admin notifications

## Testing

```bash
php artisan test       # or: ./vendor/bin/phpunit
```

## License

Proprietary — © Gutgas. Built on the [Laravel framework](https://laravel.com) (MIT).
