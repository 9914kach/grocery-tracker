# Architecture

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Frontend | React 18 + Inertia.js v2 |
| Styling | Tailwind CSS v3 |
| Build | Vite 7 + laravel-vite-plugin |
| Auth | Laravel Breeze |
| Database | MySQL 8.4 |
| Containerization | Docker (Laravel Sail) |

## Docker Services

```
compose.yaml
├── laravel.test   — PHP/Laravel app, port 80
├── vite           — Vite dev server, port 5173 (HMR)
└── mysql          — MySQL 8.4, port 3306
```

All services communicate via the `sail` bridge network using service names as hostnames (e.g. `DB_HOST=mysql`).

## Request Flow

```
Browser
  │
  ├── HTTP :80 → laravel.test (PHP-FPM + nginx)
  │     └── Inertia response (HTML shell + JSON page props)
  │
  └── WS :5173 → vite (HMR WebSocket)
        └── Asset updates pushed directly to browser
```

## Frontend

- Inertia.js connects Laravel routes to React page components with no separate API layer.
- Pages live in `resources/js/Pages/`.
- Shared UI components live in `resources/js/Components/`.
- Layouts live in `resources/js/Layouts/`.
- HMR is configured with `usePolling: true` (required for Docker on Windows) and `eager: true` glob imports (required for React Fast Refresh through Inertia).

## Backend

- Standard Laravel MVC, routes in `routes/web.php` and `routes/auth.php`.
- Domain models in `app/Models/`.
- Controllers in `app/Http/Controllers/`.

## Environment

Copy `.env.example` to `.env` and generate an app key:

```bash
docker compose up -d
docker exec grocery-tracker-laravel.test-1 php artisan key:generate
docker exec grocery-tracker-laravel.test-1 php artisan migrate --seed
```

See `Makefile` for common shortcuts.
