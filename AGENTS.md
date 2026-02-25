# AGENTS.md

## Cursor Cloud specific instructions

### Project overview

Grocery Tracker — a Laravel 12 + React 18 (Inertia.js) web application with Laravel Breeze authentication. See `composer.json` scripts for dev/test/setup commands.

### System dependencies

- **PHP 8.4+** (required by `composer.lock`; PHP 8.3 will not work)
- **Composer** for PHP package management
- **Node.js 22+** / **npm** for frontend dependencies
- **SQLite3** for local database (no MySQL needed)

### Development setup

The `.env` is configured to use SQLite (`DB_CONNECTION=sqlite`) with `database/database.sqlite`. The `.env.testing` uses in-memory SQLite (`DB_DATABASE=:memory:`). These files are gitignored and must be recreated after a fresh clone — the update script handles this.

### Running the app

- `composer dev` starts Laravel server (port 8000), queue worker, and Vite dev server (port 5173) concurrently
- Alternatively, run services individually: `php artisan serve --host=0.0.0.0 --port=8000` and `npm run dev`

### Testing

- `php artisan test` or `composer test` runs PHPUnit
- `phpunit.xml` is configured with `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:` for tests
- 8 of 25 Breeze tests fail with `ViteException: Unable to locate file in Vite manifest` — this is a **pre-existing codebase issue** caused by `eager: true` in `import.meta.glob` not producing individual page manifest entries. Tests that do not render pages (POST-based auth logic, profile updates) all pass.

### Linting

- `vendor/bin/pint --test` runs Laravel Pint code style checks (all 47 files pass)
- No ESLint or Prettier configured for JS/React

### Gotchas

- The Vite config has `server.host: '0.0.0.0'` and `hmr.host: 'localhost'` — this works correctly in the cloud VM.
- `SESSION_DRIVER`, `CACHE_STORE`, and `QUEUE_CONNECTION` all use `database` driver. The cache and sessions tables are created by migrations. If you see session/cache errors, ensure migrations have been run.
