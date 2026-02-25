# Project State

## What this project is

A personal grocery tracker that:
1. Imports order/receipt data from stores
2. Tracks price history per product per store
3. Eventually matches store SKUs to canonical products (via barcode/AI)
4. Provides insights: cheapest store, price trends, spending patterns

## Current Milestone: Foundation

### Done
- [x] Laravel 12 + React (Inertia) + Breeze auth
- [x] Docker setup (Laravel Sail) — MySQL, Vite HMR, Laravel
- [x] HMR working with React Fast Refresh (Docker/Windows config)
- [x] Domain models + migrations: stores, products, store_products, price_records, orders, order_items
- [x] `/health/db` endpoint
- [x] Makefile with common dev commands

### Next: Import Pipeline
- [ ] `POST /api/orders/import` endpoint (or Artisan command)
- [ ] Accepts a dummy JSON payload (order + line items)
- [ ] Creates/resolves store, store_products, price_records, order, order_items
- [ ] Returns created order with items

### Backlog
- [ ] Canonical product matching (barcode + AI)
- [ ] Price comparison view across stores
- [ ] Spending dashboard (charts, trends)
- [ ] Receipt scraping / store API integration

## Key Decisions

| Decision | Choice | Reason |
|---|---|---|
| Frontend | React + Inertia (not separate SPA) | Simpler auth, no API layer needed initially |
| `store_products.product_id` nullable | Yes | Decouple import from matching; import first, match later |
| Price records | Append-only | Full price history, no data loss |
| `quantity` as decimal(8,3) | Yes | Supports weight-based items (e.g. 0.350 kg) |

## Docs Index

- [ARCHITECTURE.md](docs/ARCHITECTURE.md) — tech stack, Docker setup, request flow
- [DATABASE.md](docs/DATABASE.md) — full schema with column descriptions
- [SEQUENCE_FLOW.md](docs/SEQUENCE_FLOW.md) — import pipeline and data flows
