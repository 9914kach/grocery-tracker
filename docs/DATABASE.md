# Database

> Rule: every DB change = update this file in the same commit.

## Schema Overview

```
users
stores
products
store_products  (store × product SKU)
price_records   (price history per store_product)
orders          (a shopping trip/receipt)
order_items     (line items on an order)
```

## Tables

### users
Standard Laravel Breeze user table.

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | |
| email | varchar unique | |
| password | varchar | hashed |
| email_verified_at | timestamp nullable | |
| remember_token | varchar nullable | |
| created_at / updated_at | timestamp | |

---

### stores
A physical supermarket or shop.

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | e.g. "ICA Maxi Lindhagen" |
| chain | varchar nullable | e.g. "ICA", "Coop", "Willys" |
| city | varchar nullable | |
| created_at / updated_at | timestamp | |

---

### products
Canonical product catalog. One row per unique real-world product.

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | canonical name |
| brand | varchar nullable | |
| barcode | varchar nullable unique | EAN/GTIN |
| category | varchar nullable | |
| created_at / updated_at | timestamp | |

---

### store_products
A product as listed by a specific store (SKU). The `product_id` FK is nullable — canonical matching happens later via AI/manual review.

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| store_id | bigint FK → stores | |
| product_id | bigint FK nullable → products | null until matched |
| external_id | varchar nullable | store's own item ID |
| name | varchar | name as listed by store |
| barcode | varchar nullable | |
| unit_size | varchar nullable | e.g. "500g", "1L" |
| created_at / updated_at | timestamp | |

Unique constraint: `(store_id, external_id)`.

---

### price_records
Immutable price history. A new row is appended each time a price is observed.

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| store_product_id | bigint FK → store_products | |
| price | decimal(8,2) | |
| currency | char(3) | default 'SEK' |
| recorded_at | timestamp | when price was observed |
| created_at / updated_at | timestamp | |

---

### orders
A single shopping trip or receipt.

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK → users | |
| store_id | bigint FK → stores | |
| ordered_at | timestamp | when the purchase happened |
| receipt_reference | varchar nullable | receipt number / external ID |
| notes | text nullable | |
| created_at / updated_at | timestamp | |

---

### order_items
One row per line item on a receipt.

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| order_id | bigint FK → orders | |
| store_product_id | bigint FK → store_products | |
| quantity | decimal(8,3) | supports weight-based items |
| unit_price | decimal(8,2) | price per unit at time of purchase |
| total_price | decimal(8,2) | quantity × unit_price |
| created_at / updated_at | timestamp | |

## Entity Relationships

```
users ──< orders >── stores
               │
          order_items >── store_products >── price_records
                                │
                            products (canonical, nullable)
```
