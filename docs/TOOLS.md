# Developer Tools

## DBeaver — MySQL GUI

DBeaver används för att inspektera databasen direkt.

### Skapa en anslutning

1. Öppna DBeaver
2. `Database` → `New Database Connection`
3. Välj **MySQL** → Next
4. Fyll i:

| Fält | Värde |
|---|---|
| Server Host | `localhost` |
| Port | `3306` |
| Database | `grocery_tracker` |
| Username | `sail` |
| Password | `password` |

5. Klicka **Test Connection** — DBeaver laddar ner MySQL-drivern automatiskt om den saknas
6. Klicka **Finish**

### Navigera i databasen

```
grocery_tracker
  └── Tables
        ├── orders
        ├── order_items
        ├── stores
        ├── store_products
        ├── price_records
        ├── products
        ├── users
        └── personal_access_tokens
```

Dubbelklicka på en tabell → **Data**-fliken för att se rader.

### Köra SQL

`SQL Editor` → `New SQL Script` (eller `Ctrl+]`):

```sql
-- Visa alla orders med butiksnamn
SELECT o.id, o.ordered_at, s.name AS store, o.receipt_reference
FROM orders o
JOIN stores s ON s.id = o.store_id
ORDER BY o.ordered_at DESC;

-- Visa alla store_products som saknar canonical product
SELECT sp.id, sp.name, s.name AS store, sp.external_id
FROM store_products sp
JOIN stores s ON s.id = sp.store_id
WHERE sp.product_id IS NULL;

-- Prishistorik per store_product
SELECT sp.name, pr.price, pr.currency, pr.recorded_at
FROM price_records pr
JOIN store_products sp ON sp.id = pr.store_product_id
ORDER BY pr.recorded_at DESC;
```

---

## Postman — API-testverktyg

### Skapa ett API-token

Kör detta i terminalen (krävs en gång per miljö):

```bash
docker exec grocery-tracker-laravel.test-1 php artisan tinker --execute="echo App\Models\User::first()->createToken('postman')->plainTextToken;"
```

Du får tillbaka en sträng som ser ut så här:

```
1|abc123xyz...
```

Spara hela strängen — det är din Bearer token.

> Tokens sparas i tabellen `personal_access_tokens` i databasen.

---

### Konfigurera en request i Postman

#### 1. Skapa ny request

- Klicka **New** → **HTTP Request**

#### 2. Method + URL

- Byt `GET` till **POST**
- URL: `http://localhost/api/orders/import`

#### 3. Auth-fliken

- Type: **Bearer Token**
- Token: klistra in din token

#### 4. Headers-fliken

Lägg till:

| Key | Value |
|---|---|
| `Accept` | `application/json` |

(`Content-Type: application/json` sätts automatiskt när du väljer JSON i Body.)

#### 5. Body-fliken

- Välj **raw**
- Välj **JSON** i dropdown-menyn till höger

Klistra in:

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
      "external_reference": "7310865004703"
    },
    {
      "raw_name": "Ägg 12-pack",
      "quantity": 1,
      "unit_price": 39.90,
      "external_reference": "7311041010878"
    }
  ]
}
```

#### 6. Send

Klicka **Send**.

Förväntad respons `201 Created`:
```json
{
  "order_id": 1,
  "status": "created",
  "items_count": 2
}
```

Skicka **samma request igen** → `200 already_imported`:
```json
{
  "order_id": 1,
  "status": "already_imported"
}
```

---

### Spara som Collection

1. Klicka på **Save** (uppe till höger i request-fliken)
2. Skapa en ny collection: **grocery-tracker**
3. Spara requesten som **Import Order**

Du kan sedan lägga till fler requests i samma collection allt eftersom API:et växer.

---

### Testa validering

Skicka en payload med `items` tom för att se ett `422`-svar:

```json
{
  "store": "ica",
  "ordered_at": "2024-01-15T10:30:00",
  "items": []
}
```

Förväntad respons `422`:
```json
{
  "message": "The items field must have at least 1 items.",
  "errors": {
    "items": ["The items field must have at least 1 items."]
  }
}
```
