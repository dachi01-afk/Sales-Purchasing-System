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
| **Xendit Payment** | Invoice API, Payment Link (QRIS/VA/E-Wallet), Webhook auto-update, Receipt auto-creation |
| **DataTables (Yajra)** | Server-side processing, search, sort, pagination, export Excel/CSV di semua halaman Laporan |
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

## Xendit Payment Gateway Integration

Integrasi **Xendit Invoice API** untuk menerima pembayaran online melalui QRIS, Virtual Account, dan E-Wallet (DANA/OVO/GoPay).

### Fitur Xendit

| Fitur | Keterangan |
|---|---|
| **Send Payment Link** | Kirim link pembayaran Xendit ke customer dari halaman Sales Invoice |
| **Auto Webhook** | Status invoice otomatis update saat `invoice.paid` dari Xendit |
| **Auto Receipt** | Receipt terbuat otomatis saat pembayaran sukses |
| **Refresh Status** | Webhook handler mendukung dual format (production + Simulate Callback) |

### Setup Environment (.env)

```bash
XENDIT_API_KEY=xnd_development_...
XENDIT_WEBHOOK_TOKEN=your_webhook_verification_token
ASSET_URL=https://your-ngrok-domain.ngrok-free.dev
```

### Setup di Xendit Dashboard

1. **Login** ke [Xendit Dashboard Sandbox](https://dashboard.xendit.co/sandbox)
2. **Settings → Callback URL**
   - Isi: `https://your-domain.ngrok-free.dev/api/xendit/webhook`
   - Callback Token: isi dengan nilai `XENDIT_WEBHOOK_TOKEN` dari `.env`
3. **Settings → API Key** → copy `Development` API Key ke `.env`

### Alur Pembayaran

```
Sales Invoice (draft)
    ↓  [Send Payment Link]
Sales Invoice (pending_payment) + link Xendit
    ↓  [Customer buka link & bayar via QRIS/VA/EWallet]
Xendit kirim Webhook → /api/xendit/webhook
    ↓  [Webhook Controller]
Sales Invoice (paid) + Receipt auto-created
```

### Cara Kirim Payment Link

1. Buka menu **Sales Invoices**
2. Pilih invoice status **draft**
3. Klik tombol **Send Payment Link** (warna hijau)
4. Invoice berubah jadi `pending_payment` + link Xendit muncul
5. Klik **Open Payment Page** untuk test bayar

### Test Webhook (Sandbox)

Xendit sandbox tidak otomatis kirim `invoice.paid` setelah simulasi bayar. Gunakan **Simulate Callback**:

1. Buka [Xendit Dashboard](https://dashboard.xendit.co/sandbox) → **Invoices**
2. Cari invoice status `pending_payment`
3. Klik invoice → tombol **Simulate Callback**
4. Pilih event **`invoice.paid`** → Send
5. Response: `{"status": "ok"}`
6. Refresh halaman Laravel → invoice status `paid`, Receipt auto terbuat

### Route Xendit

| Method | URL | Fungsi |
|---|---|---|
| POST | `/sales-invoices/{id}/send-payment-link` | Kirim link pembayaran Xendit |
| POST | `/api/xendit/webhook` | Webhook callback dari Xendit |

### File Terkait

| File | Fungsi |
|---|---|
| `app/Services/XenditService.php` | Service class untuk panggil Xendit API |
| `app/Http/Controllers/Api/XenditWebhookController.php` | Handler webhook `invoice.paid` |
| `app/Http/Controllers/SalesInvoiceController.php` | Action Send Payment Link |
| `config/xendit.php` | Konfigurasi API key & webhook token |
| `routes/api.php` | Route webhook `POST /api/xendit/webhook` |
| `bootstrap/app.php` | CSRF exception + trustProxies |

## Yajra DataTables Integration

Integrasi **Yajra DataTables (server-side)** untuk semua halaman Laporan agar mendukung pencarian, sorting, dan pagination real-time tanpa refresh halaman.

### Fitur DataTables

| Fitur | Keterangan |
|---|---|
| **Server-side processing** | Semua data diproses di backend (tidak loading semua data sekaligus) |
| **Search otomatis** | Kolom pencarian di setiap tabel laporan |
| **Sort multi-kolom** | Klik header tabel untuk sorting ascending/descending |
| **Pagination** | Pagination dengan jumlah entri per halaman bisa diatur |
| **Date filter** | Filter tanggal tetap berfungsi (Purchase & Sales Reports) |
| **Export Excel/CSV** | Tombol export di atas tabel, download file `.xlsx` / `.csv` via DataTables Buttons |

### Halaman yang menggunakan DataTables

| Halaman | Route | Data |
|---|---|---|
| **Product Report** | `/reports/products` | Products (SKU, Name, Price) |
| **Purchase Reports** | `/reports/purchases` | Purchase Orders (PO#, Date, Vendor, Items, Status) |
| **Sales Reports** | `/reports/sales` | Sales Orders (SO#, Date, Customer, Items, Status) |

### Tech Stack DataTables

| Komponen | Keterangan |
|---|---|
| **Package** | `yajra/laravel-datatables-oracle` (Laravel server-side) |
| **JS Library** | DataTables 2.x + jQuery 3.7 (via CDN) |
| **CSS Theme** | Tailwind CSS integration (`dataTables.tailwindcss.css`) |
| **Export** | DataTables Buttons 3.x + JSZip (via CDN) — client-side export |

### File Terkait DataTables

| File | Fungsi |
|---|---|
| `app/Http/Controllers/ReportController.php` | Method `products()`, `productsData()`, `purchasesData()`, `salesData()` |
| `resources/views/reports/products.blade.php` | View Product Report dengan DataTables + export buttons |
| `resources/views/reports/purchases.blade.php` | View Purchase Reports dengan DataTables + export buttons |
| `resources/views/reports/sales.blade.php` | View Sales Reports dengan DataTables + export buttons |
| `resources/views/layouts/app.blade.php` | CDN jQuery + DataTables + Buttons + JSZip CSS/JS |

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
| **Reports** | purchases/sales/financial/products | ✓/✓/✓/✓ | -/-/-/- | -/-/-/- | -/-/-/- | ✓/✓/✓/✓ |

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

# Xendit Payment
POST         /api/xendit/webhook             # Webhook Xendit (public)
POST         /sales-invoices/{id}/send-payment-link  # can:sales_invoices.edit

# Reports
GET          /reports/products               # can:products.view
GET          /reports/purchases              # can:reports.purchases
GET          /reports/sales                  # can:reports.sales
GET          /reports/financial              # can:reports.financial
GET          /reports/products/data          # DataTables JSON (internal)
GET          /reports/purchases/data         # DataTables JSON (internal)
GET          /reports/sales/data             # DataTables JSON (internal)
```
