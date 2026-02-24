# Grocery Tracker – System Architecture

> Portfolio project demonstrating senior backend design, scalable architecture, and multi-user support.

---

## 🏗 Overview

This project is designed as an **API-first, multi-user platform** to track grocery orders, products, and nutrition.  

Key principles:

- Separation of concerns: canonical products vs store products vs user orders  
- User-scoped data: each user owns their orders and order items  
- Global data: canonical products, nutrition profiles, product aliases  
- Event-driven enrichment: AI-ready, queue-based processing  
- Merge-friendly canonical product model

---

## 👤 Users & Orders

**User-owned data:**

- `users`
- `orders`
- `order_items`

**Relationships:**

```text
User → Order → OrderItem → StoreProduct → Product
