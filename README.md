# 🛒 Grocery Tracker

A full-stack grocery tracking system built with Laravel, MySQL and Vite.  
The goal of this project is to simulate a professional production environment with CI, code quality tooling and containerized development.

---

# 🚀 Tech Stack

- PHP 8.x
- Laravel
- MySQL 8
- Node + Vite
- Docker (Laravel Sail)
- TailwindCSS

---

# 🐳 Requirements

You only need:

- Docker
- Docker Compose

No global PHP, MySQL or Node installation required.

---

# ⚡ Quick Start

```bash
# 1. Clone repository
git clone https://github.com/9914kach/grocery-tracker.git
cd grocery-tracker

# 2. Copy environment file
cp .env.example .env

# 3. Start containers
docker compose up -d

# 4. Install backend dependencies
docker compose exec laravel.test composer install
docker compose exec laravel.test php artisan key:generate

# 5. Run migrations
docker compose exec laravel.test php artisan migrate

# 6. Install frontend dependencies
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run dev
```

Application should now be running at:

```
http://localhost
```

---

# 🧠 Development Workflow

We follow a simplified professional workflow:

- `main` → production-ready code
- `dev` → integration branch
- `feature/*` → feature branches

Example:

```bash
git checkout -b feature/add-receipt-import
```

---

# 🛠 Common Commands

If using Makefile:

```bash
make up        # Start containers
make down      # Stop containers
make fresh     # Fresh migrate
make test      # Run tests
make lint      # Run linters
```

Without Makefile:

```bash
docker compose exec laravel.test php artisan test
docker compose exec laravel.test php artisan migrate:fresh --seed
```

---

# 🧪 Testing

Run backend tests:

```bash
docker compose exec laravel.test php artisan test
```

CI will automatically run tests and linting on pull requests.

---

# 📁 Project Structure

```
app/            → Core application logic
routes/         → Web & API routes
database/       → Migrations & seeders
resources/      → Frontend (JS, CSS, Blade)
docker/         → Container configuration
docs/           → Project documentation
```

---

# 🔍 Code Quality

This project uses:

- Laravel Pint (formatter)
- PHPStan (static analysis)
- ESLint + Prettier (frontend)
- GitHub Actions (CI pipeline)

All pull requests must pass CI before merge.

---

# 🎯 Project Vision

This project is built to:

- Simulate real workplace standards
- Practice containerized development
- Implement CI/CD pipelines
- Maintain clean architecture principles
- Be production deployable

---

# 📌 Status

🚧 Active Development  
Next milestone: CI pipeline + strict static analysis
