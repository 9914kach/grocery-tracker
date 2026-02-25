# Grocery Tracker – Filstruktur (Laravel 12.x + React Starter Kit)

> Rekommenderad filstruktur för ett Laravel 12-projekt med React/Inertia starter kit, anpassad för Grocery Tracker.

---

## Skapa projektet

```bash
composer global require laravel/installer
laravel new grocery-tracker
# Välj React starter kit vid prompt
cd grocery-tracker
npm install && npm run build
```

---

## Översikt – Hela strukturen

```
grocery-tracker/
├── app/
│   ├── Actions/
│   │   └── Fortify/                    # Auth-anpassningar (CreateNewUser, etc.)
│   ├── Events/
│   │   └── OrderEnriched.php           # Event-driven enrichment
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   └── StoreProductController.php
│   │   │   └── OrderController.php    # Web/Inertia controllers
│   │   └── Middleware/
│   ├── Jobs/
│   │   └── EnrichOrderItemJob.php      # Queue-baserad bearbetning
│   ├── Models/
│   │   ├── User.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Product.php                 # Canonical product
│   │   └── StoreProduct.php
│   ├── Policies/
│   │   ├── OrderPolicy.php
│   │   └── OrderItemPolicy.php
│   └── Providers/
│
├── bootstrap/
├── config/
│   ├── fortify.php                     # Auth-features
│   └── ...
│
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_products_table.php
│   │   ├── 0001_01_01_000002_create_store_products_table.php
│   │   ├── 0001_01_01_000003_create_orders_table.php
│   │   └── 0001_01_01_000004_create_order_items_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   └── ProductSeeder.php
│   └── factories/
│       ├── UserFactory.php
│       ├── OrderFactory.php
│       └── ProductFactory.php
│
├── public/
├── resources/
│   ├── js/
│   │   ├── components/                 # Återanvändbara React-komponenter
│   │   │   ├── ui/                     # shadcn/ui
│   │   │   ├── OrderCard.tsx
│   │   │   ├── OrderItemList.tsx
│   │   │   └── ProductSearch.tsx
│   │   ├── hooks/
│   │   │   └── useOrders.ts
│   │   ├── layouts/
│   │   │   ├── app/
│   │   │   │   ├── app-sidebar-layout.tsx
│   │   │   │   └── app-header-layout.tsx
│   │   │   └── auth/
│   │   ├── lib/
│   │   ├── pages/
│   │   │   ├── Dashboard.tsx
│   │   │   ├── Orders/
│   │   │   │   ├── Index.tsx
│   │   │   │   ├── Show.tsx
│   │   │   │   └── Create.tsx
│   │   │   └── Products/
│   │   │       └── Index.tsx
│   │   └── types/
│   │       ├── order.ts
│   │       ├── product.ts
│   │       └── user.ts
│   └── views/
│       └── app.blade.php
│
├── routes/
│   ├── api.php                         # API-routes (orders, products)
│   ├── web.php                         # Inertia/Web-routes
│   └── channels.php
│
├── storage/
├── tests/
│   ├── Feature/
│   │   ├── OrderTest.php
│   │   └── ProductTest.php
│   └── Unit/
│       └── OrderItemTest.php
│
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

## Viktiga mappar – Beskrivning

### `app/Models/`
- **User** – Användare (Laravel default)
- **Product** – Kanonisk produkt (global)
- **StoreProduct** – Butiksspecifik produkt (länk till Product)
- **Order** – Användarens order (user-scoped)
- **OrderItem** – Orderrad (Order → OrderItem → StoreProduct → Product)

### `app/Http/Controllers/`
- **Api/** – API-controllers för externa klienter
- **OrderController** – Web/Inertia för ordersidor

### `resources/js/`
- **components/** – React-komponenter (inkl. shadcn/ui)
- **pages/** – Inertia-sidor (Orders, Products, Dashboard)
- **types/** – TypeScript-typer
- **layouts/** – App- och auth-layouts

### `database/migrations/`
- Migrations för users, products, store_products, orders, order_items

---

## API vs Web-routes

| Typ   | Fil      | Användning                          |
|-------|----------|-------------------------------------|
| Web   | `web.php` | Inertia-sidor, auth, dashboard      |
| API   | `api.php` | REST API för orders, products       |

---

## Referens

- [Laravel 12 Starter Kits](https://laravel.com/docs/12.x/starter-kits)
- [Inertia.js](https://inertiajs.com/)
- [shadcn/ui](https://ui.shadcn.com/)
