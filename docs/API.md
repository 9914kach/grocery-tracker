# API Reference

## Authentication

All API endpoints require a Sanctum token passed as a Bearer header:

```
Authorization: Bearer <token>
```

Tokens are created via Laravel Sanctum. For manual testing with a logged-in browser session, session cookies also work.

---

## POST /api/orders/import

Import a grocery order from an external source (e.g. parsed receipt email).

### Request

```
POST /api/orders/import
Content-Type: application/json
Authorization: Bearer <token>
```

#### Body

```json
{
  "store": "ica",
  "ordered_at": "2024-01-15T10:30:00",
  "receipt_reference": "REC-001",
  "items": [
    {
      "raw_name": "Mjölk 3% 1L",
      "quantity": 2,
      "unit_price": 13.90,
      "line_total": 27.80,
      "external_reference": "7310865004703",
      "weight_grams": null
    }
  ]
}
```

#### Field reference

| Field | Type | Required | Notes |
|---|---|---|---|
| `store` | string | yes | Chain name, e.g. `"ica"`, `"coop"`, `"willys"` |
| `ordered_at` | datetime string | yes | ISO 8601 or any `strtotime`-parseable string |
| `receipt_reference` | string | no | Receipt number or external ID |
| `items` | array | yes | Min 1 item |
| `items.*.raw_name` | string | yes | Product name as it appears on the receipt |
| `items.*.quantity` | number | yes | Supports decimals for weight-based items |
| `items.*.unit_price` | number | yes | Price per unit in SEK |
| `items.*.line_total` | number | no | If omitted, computed as `quantity × unit_price` |
| `items.*.external_reference` | string | no | Store's own article number / barcode |
| `items.*.weight_grams` | integer | no | Used to set `store_products.unit_size` |

### Responses

#### 201 Created

Order was created successfully.

```json
{
  "order_id": 42,
  "status": "created",
  "items_count": 3
}
```

#### 200 OK — Already Imported

An order with the same content was previously imported (idempotent).

```json
{
  "order_id": 42,
  "status": "already_imported"
}
```

#### 422 Unprocessable Entity

Validation failed.

```json
{
  "message": "The store field is required.",
  "errors": {
    "store": ["The store field is required."]
  }
}
```

---

## Idempotency

Each import is fingerprinted with an `import_hash`:

```
sha256(user_id . store_slug . date(ordered_at) . sorted(raw_name:unit_price per item))
```

Sending the same payload twice returns `200 already_imported` without creating duplicate records.

---

## Creating a token

```bash
docker exec grocery-tracker-laravel.test-1 php artisan tinker --execute="echo App\Models\User::first()->createToken('postman')->plainTextToken;"
```

Returns a string like `1|abc123...` — use as `Bearer <token>` in Authorization header.

Tokens are stored in the `personal_access_tokens` table.

See [TOOLS.md](TOOLS.md) for step-by-step Postman and DBeaver setup guides.

---

## Testing with curl

```bash
curl -X POST http://localhost/api/orders/import \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "store": "ica",
    "ordered_at": "2024-01-15T10:30:00",
    "items": [
      {
        "raw_name": "Mjölk 3% 1L",
        "quantity": 1,
        "unit_price": 13.90,
        "external_reference": "7310865004703"
      }
    ]
  }'
```
