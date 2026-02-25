# Sequence Flow

## System Pipeline Overview

```
Import → Matching → Enrichment → Analytics
```

Each stage is independent. Import never blocks on matching. Matching never blocks on enrichment.

---

## Stage 1: Import Pipeline — DONE

`POST /api/orders/import` — see [API.md](API.md) for full contract.

```
POST /api/orders/import (Bearer token)
  │
  ├── ImportOrderRequest (validate)
  │
  └── OrderImportService::import()
        │
        ├── Compute import_hash → check idempotency
        │     └── if exists → return 200 already_imported
        │
        └── DB::transaction()
              ├── 1. Store::firstOrCreate by chain
              ├── 2. Order::create + import_hash
              └── 3. For each item:
                    ├── StoreProduct::firstOrCreate
                    │     └── match by (store_id, external_id) or (store_id, name)
                    ├── PriceRecord::create  (recorded_at = ordered_at)
                    └── OrderItem::create
```

At this stage `store_products.product_id` is null. Matching is a separate async stage.

---

## Stage 2: Matching Pipeline — NEXT

Goal: link `store_products` to canonical `products` without touching the import flow.

```
After order import (async, non-blocking)
  │
  └── Queue job: AttemptAutoMatchOrderItems
        │
        ├── For each store_product where product_id IS NULL:
        │     │
        │     ├── 1. Exact barcode match → products.barcode
        │     │     └── if found → UPDATE store_products SET product_id = ?
        │     │
        │     └── 2. No match → INSERT product_match_reviews (status = pending)
        │
        └── Review UI: React page "Needs Review (N)"
              └── User resolves → UPDATE store_products SET product_id = ?
```

Matching is fire-and-forget after import. Import never waits for matching.

---

## Stage 3: Enrichment — FUTURE

```
products (matched)
  │
  └── AI enrichment job
        ├── Nutrition data
        ├── Classification / category
        └── Tags (organic, lactose-free, etc.)
```

---

## Price History Query

```
store_products
  └── price_records (ordered by recorded_at DESC)
        └── latest price per store_product
              └── compare across stores via shared product_id
```

---

## Data Integrity Rules

| Rule | Where enforced |
|---|---|
| One order per unique payload | `orders.import_hash` (sha256, unique index) |
| store_products scoped per store | `UNIQUE(store_id, external_id)` |
| price_records are append-only | No UPDATE — new row per observed price |
| product_id nullable until matched | FK nullable, set async by matching job |
