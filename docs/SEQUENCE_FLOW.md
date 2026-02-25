# Sequence Flow

## Import Pipeline (Milestone 1)

The core data flow when a user imports an order/receipt.

```
Client (JSON payload)
  │
  └── POST /api/orders/import
        │
        ├── 1. Resolve store
        │     └── find or create stores row by name/chain
        │
        ├── 2. For each line item:
        │     ├── find or create store_products
        │     │     └── match by external_id or barcode
        │     │
        │     └── append price_records if price changed
        │
        ├── 3. Create order
        │     └── orders row with user_id + store_id + ordered_at
        │
        └── 4. Create order_items
              └── one row per line item → store_product_id
```

At this stage `product_id` on `store_products` is left null. Canonical matching happens in a later phase.

---

## Canonical Matching (Milestone 2 — future)

```
store_products (product_id = null)
  │
  └── Matching pipeline
        ├── Barcode lookup → products.barcode
        ├── Name similarity (fuzzy / AI)
        └── Manual review UI
              │
              └── UPDATE store_products SET product_id = ?
```

---

## Price History Query

```
store_products
  └── price_records (ordered by recorded_at DESC)
        └── latest price per store_product
              └── compare across stores for same product_id
```
