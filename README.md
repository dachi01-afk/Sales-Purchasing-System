# SPM System (Sales & Purchasing Management System)

Aplikasi manajemen siklus pembelian dan penjualan full-stack berbasis **Laravel 13**, **Flowbite**, **Tailwind CSS v4**, dan **MySQL**. Dilengkapi role-based access control menggunakan Spatie Laravel Permission.

## Fitur Utama

| Modul | Fitur |
|---|---|
| **Master Data** | CRUD Products, Vendors, Customers |
| **Purchasing Cycle** | Purchase Request → PO → Goods Receipt → Invoice → Return |
| **Sales Cycle** | SO → DO → Invoice → Return → Receipt |
| **Reports** | Purchases, Sales, Financial |
| **Dashboard** | Monthly stats, latest PO/SO, pending summary (role-based) |
| **RBAC** | 5 roles with granular permissions per module |
| **UI** | Full dark mode, Flowbite components, responsive |

## Tech Stack

- **Backend:** Laravel 13, Spatie Laravel Permission
- **Frontend:** Blade, Tailwind CSS v4, Flowbite, AlpineJS v3
- **Database:** MySQL
- **Auth:** Laravel Breeze (Blade stack)

## Struktur Database

### Purchasing Cycle (Header-Detail)

```
purchase_requests ─→ purchase_orders ─→ goods_receipts ─→ purchase_invoices
                                                └─── purchase_returns
```

### Sales Cycle (Header-Detail)

```
sales_orders ─→ delivery_orders ─→ sales_invoices
                         └─── sales_returns
```

Setiap transaksi menggunakan pola **Header-Detail**:
- **Header:** data umum (date, status, total)
- **Detail:** data per-item (sku, qty, price, subtotal)

## Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/dachi01-afk/Sales-Purchasing-System.git
cd Sales-Purchasing-System

# 2. Install dependencies PHP
composer install

# 3. Copy environment
cp .env.example .env

# 4. Generate key
php artisan key:generate

# 5. Setup database (edit .env sesuai konfigurasi MySQL)
# DB_DATABASE=sales_purchasing_system
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Buat database
mysql -u root -e "CREATE DATABASE sales_purchasing_system"

# 7. Migrate + seed
php artisan migrate --seed

# 8. Install dependencies frontend
npm install

# 9. Build CSS
npm run build

# 10. Jalankan aplikasi
composer run dev
```

## User Akun (Dummy Seeder)

| Email | Password | Role |
|---|---|---|
| admin@test.local | password | admin |
| purchasing-staff@test.local | password | purchasing-staff |
| sales-staff@test.local | password | sales-staff |
| finance@test.local | password | finance |
| manager@test.local | password | manager |

## Authorization Matrix

| Module | Permission | Admin | Purchasing | Sales | Finance | Manager |
|---|---|---|---|---|---|---|
| **Products** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/-/-/- | ✓/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Vendors** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/✓/- | -/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Customers** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- | ✓/-/-/- |
| **Purchase Requests** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/✓/- | -/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Purchase Orders** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/✓/- | -/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Goods Receipts** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/✓/- | -/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Purchase Invoices** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/-/-/- | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- |
| **Purchase Returns** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/-/- | -/-/-/- | -/-/-/- | ✓/-/-/- |
| **Sales Orders** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- | ✓/-/-/- |
| **Delivery Orders** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- | ✓/-/-/- |
| **Sales Invoices** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/-/-/- | ✓/✓/✓/- | ✓/-/-/- |
| **Sales Returns** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/✓/-/- | -/-/-/- | ✓/-/-/- |
| **Receipts** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- |
| **Reports** | purchases/sales/financial | ✓/✓/✓ | -/-/- | -/-/- | -/-/- | ✓/✓/✓ |

Detail permission selengkapnya: [docs/authorization.md](docs/authorization.md)

## Route Summary

```
GET/POST/... /login                          # Login
GET          /dashboard                      # Dashboard

# Master Data
/resource    /products                       # can:products.view
/resource    /vendors                        # can:vendors.view
/resource    /customers                      # can:customers.view

# Purchasing
/resource    /purchase-requests              # can:purchase_requests.view
/resource    /purchase-orders                # can:purchase_orders.view
/resource    /goods-receipts                 # can:goods_receipts.view
/resource    /purchase-invoices              # can:purchase_invoices.view
/resource    /purchase-returns               # can:purchase_returns.view

# Sales
/resource    /sales-orders                   # can:sales_orders.view
/resource    /delivery-orders                # can:delivery_orders.view
/resource    /sales-invoices                 # can:sales_invoices.view
/resource    /sales-returns                  # can:sales_returns.view
/resource    /receipts                       # can:receipts.view

# Reports
GET          /reports/purchases              # can:reports.purchases
GET          /reports/sales                  # can:reports.sales
GET          /reports/financial              # can:reports.financial
```
