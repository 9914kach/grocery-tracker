# Project State

---

## Vision

This project is evolving into a structured, AI-assisted grocery intelligence system.

The long-term goal is to:

1. Capture structured purchase data reliably.
2. Normalize store-level SKUs into a global canonical product catalog.
3. Track price history over time per store and per product.
4. Enrich products with AI (nutrition, classification, tagging).
5. Provide actionable insights: cheapest store, trends, macro estimation, spending patterns.

The architecture is intentionally layered:

Import → Matching → Enrichment → Analytics → Automation

MVP focuses strictly on deterministic import and structural integrity.

---

## Current Milestone: Foundation (MVP Scope)

### Done
- [x] Laravel 12 + React (Inertia) + Breeze auth
- [x] Docker setup (Laravel Sail) — MySQL, Vite HMR, Laravel
- [x] HMR working with React Fast Refresh (Docker/Windows config)
- [x] Domain models + migrations: stores, products, store_products, price_records, orders, order_items
- [x] `/health/db` endpoint
- [x] Makefile with common dev commands

---

### Import Pipeline — DONE

- [x] `POST /api/orders/import` endpoint
- [x] FormRequest validation
- [x] OrderImportService with DB transaction
- [x] Idempotency via `import_hash` (sha256)
- [x] find-or-create store, store_products, price_records
- [x] create order + order_items
- [x] Laravel Sanctum API tokens (`HasApiTokens` on User, `personal_access_tokens` table)
- [x] 3 feature tests (21 assertions, all green)
- [x] Verified end-to-end via Postman + DBeaver

Import is deterministic, transactional, and idempotent.

---

### Frontend — DONE

- [x] Dashboard with stats grid + recent orders table
- [x] Orders list (paginated)
- [x] Review page — unmatched store_products with amber badge in nav
- [x] Shared `needsReviewCount` via HandleInertiaRequests middleware
- [x] Clean/minimal design (Linear-style, bg-gray-50, border cards)

---

## MVP Definition of Done

The MVP is considered complete when:

1. Orders can be imported reliably via JSON payload.
2. Import is idempotent and safe to retry.
3. All foreign keys and constraints enforce integrity.
4. Price history is captured for every store_product.
5. store_products remain decoupled from canonical products.
6. Feature tests cover the import flow.
7. System runs fully inside Docker via a single command.
8. Documentation reflects the implemented architecture.

The MVP explicitly does NOT require:

- AI matching
- Background queues
- Analytics dashboards
- Receipt scraping
- Linux VM deployment
- CI/CD pipeline
- Cross-store comparison UI

The MVP goal is correctness and structural clarity — not intelligence.

---

## Next Milestone: Matching Pipeline

Goal:
Introduce canonical product linking without breaking deterministic import.

Planned:

- [ ] Queue job: `AttemptAutoMatchOrderItems`
- [ ] Barcode-based exact matching first
- [ ] Migration + model: `product_match_reviews`
- [ ] React page: "Needs Review (N)" for manual resolution

Matching must be asynchronous and non-blocking.

Import remains independent of matching.

---

## Future Milestone: Deployment

Goal: Deploy the application to a live server with a custom domain.

Planned:

- [ ] Provision a VPS (Hetzner or DigitalOcean)
- [ ] Set up nginx + PHP-FPM (no Sail in production)
- [ ] Configure MySQL on server
- [ ] Set up SSL via Let's Encrypt + Certbot
- [ ] Purchase and configure a custom domain
- [ ] Production `.env` (APP_ENV=production, APP_DEBUG=false)
- [ ] `npm run build` Vite production assets
- [ ] GitHub Actions CI/CD — auto-deploy on push to `main`
- [ ] Queue worker for background jobs (matching pipeline)

Prerequisites: Matching pipeline must be stable before deployment.

---

## Backlog (Post-MVP Features)

- [ ] Canonical product matching (AI/fuzzy name)
- [ ] AI enrichment (nutrition, tags, classification)
- [ ] Price comparison view across stores
- [ ] Spending dashboard (charts, trends)
- [ ] Receipt scraping / store API integration
- [ ] Intelligent automation (price alerts, reorder suggestions)

---

## Future Hardening (Not MVP Scope)

These improvements are intentionally postponed:

- Background job observability & metrics
- Structured logging + request IDs
- CI/CD pipeline (GitHub Actions)
- Linux VM deployment environment
- Shared internal libraries extraction

They will be implemented once matching is stable.

---

## Key Decisions

| Decision | Choice | Reason |
|---|---|---|
| Frontend | React + Inertia | Simpler auth, no separate API layer required |
| `store_products.product_id` nullable | Yes | Decouple import from matching |
| Price records | Append-only | Full price history, no data loss |
| `quantity` as decimal(8,3) | Yes | Supports weight-based items |
| Import logic | Transactional | Guarantees consistency |
| Matching | Asynchronous | Prevents coupling import to intelligence |

---

## Docs Index

- [ARCHITECTURE.md](docs/ARCHITECTURE.md) — tech stack, Docker setup, request flow
- [DATABASE.md](docs/DATABASE.md) — full schema with column descriptions
- [SEQUENCE_FLOW.md](docs/SEQUENCE_FLOW.md) — import pipeline and data flows
- [API.md](docs/API.md) — API contract, field reference, responses
- [TOOLS.md](docs/TOOLS.md) — DBeaver + Postman setup guides