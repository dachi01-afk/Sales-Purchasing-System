# SPM System (Sales & Purchasing Management System)

Aplikasi manajemen siklus pembelian dan penjualan full-stack berbasis **Laravel 13**, **Flowbite**, **Tailwind CSS v4**, dan **MySQL**. Dilengkapi role-based access control menggunakan Spatie Laravel Permission.

## Fitur Utama

| Modul | Fitur |
|---|---|
| **Master Data** | CRUD Barang, Vendor, Customer |
| **Purchasing Cycle** | Permintaan Pembelian → PO → Penerimaan → Invoice → Retur |
| **Sales Cycle** | SO → DO → Invoice → Retur → Kwitansi |
| **Laporan** | Pembelian, Penjualan, Keuangan |
| **Dashboard** | Statistik bulan ini, PO/SO terbaru, ringkasan pending (role-based) |
| **RBAC** | 5 role dengan permission granular per modul |
| **UI** | Dark mode penuh, Flowbite components, responsive |

## Tech Stack

- **Backend:** Laravel 13, Spatie Laravel Permission
- **Frontend:** Blade, Tailwind CSS v4, Flowbite, AlpineJS v3
- **Database:** MySQL
- **Auth:** Laravel Breeze (Blade stack)

## Struktur Database

### Master Data Group

```
m_barang ─┬── detail_permintaan
           ├── detail_po
           ├── detail_penerimaan
           ├── detail_invoice_purchasing
           ├── detail_retur_purchasing
           ├── detail_so
           ├── detail_do
           ├── detail_invoice_sales
           └── detail_retur_sales

m_vendor   ─── t_purchase_order
m_customer ─── t_sales_order
```

### Purchasing Cycle

```
Permintaan → PO → Penerimaan → Invoice Purchasing
                  └── Retur Purchasing
```

Setiap transaksi menggunakan pola **Header-Detail**:
- **Header:** menyimpan data umum (nomor faktur, tanggal, total)
- **Detail:** menyimpan data per-item (sku, kuantitas, harga, diskon)

### Sales Cycle

```
SO → DO → Invoice Sales
     └── Retur Sales
```

Pola header-detail yang sama.

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

## Struktur Folder

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Master/          # BarangController, VendorController, CustomerController
│   │   ├── Purchasing/      # PermintaanController, POController, PenerimaanController, InvoicePurchasingController, ReturPurchasingController
│   │   ├── Sales/           # SOController, DOController, InvoiceSalesController, ReturSalesController, KwitansiController
│   │   ├── Laporan/         # LaporanPembelianController, LaporanPenjualanController, LaporanKeuanganController
│   │   └── DashboardController.php
│   └── Requests/            # Form request validasi per modul
├── Models/
│   ├── Master/              # Barang, Vendor, Customer
│   ├── Purchasing/          # Permintaan, PO, Penerimaan, InvoicePurchasing, ReturPurchasing
│   └── Sales/               # SO, DO, InvoiceSales, ReturSales, Kwitansi
│
database/
├── migrations/              # Semua migration
└── seeders/
    ├── RolePermissionSeeder.php
    └── UserSeeder.php

resources/views/
├── layouts/
│   ├── app.blade.php        # Layout utama (sidebar + navbar + dark mode)
│   └── guest.blade.php      # Layout login (Flowbite style)
├── master/                  # barang/, vendor/, customer/
├── purchasing/              # permintaan/, po/, penerimaan/, invoice/, retur/
├── sales/                   # so/, do/, invoice/, retur/, kwitansi/
├── laporan/                 # pembelian/, penjualan/, keuangan/
├── auth/                    # login.blade.php (tanpa register/forgot-password)
├── components/              # delete-modal reusable
├── dashboard.blade.php
└── navigation.blade.php

routes/
├── web.php                  # Semua route terproteksi middleware can:
└── auth.php                 # Route register & forgot-password di-comment
```

## Authorization Matrix

| Modul | Permission | Admin | Purchasing | Sales | Finance | Manager |
|---|---|---|---|---|---|---|
| **Barang** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/-/-/- | ✓/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Vendor** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/✓/- | -/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Customer** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- | ✓/-/-/- |
| **Permintaan** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/✓/- | -/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **PO** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/✓/- | -/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Penerimaan** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/✓/- | -/-/-/- | ✓/-/-/- | ✓/-/-/- |
| **Invoice Purchasing** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/-/-/- | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- |
| **Retur Purchasing** | view/create/edit/delete | ✓/✓/✓/✓ | ✓/✓/-/- | -/-/-/- | -/-/-/- | ✓/-/-/- |
| **SO** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- | ✓/-/-/- |
| **DO** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- | ✓/-/-/- |
| **Invoice Sales** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/-/-/- | ✓/✓/✓/- | ✓/-/-/- |
| **Retur Sales** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | ✓/✓/-/- | -/-/-/- | ✓/-/-/- |
| **Kwitansi** | view/create/edit/delete | ✓/✓/✓/✓ | -/-/-/- | -/-/-/- | ✓/✓/✓/- | ✓/-/-/- |
| **Laporan** | pembelian/penjualan/keuangan | ✓/✓/✓ | -/-/- | -/-/- | -/-/- | ✓/✓/✓ |

Detail permission selengkapnya: [docs/authorization.md](docs/authorization.md)

## Route Summary

```
GET/POST/... /login                          # Login
GET          /dashboard                      # Dashboard

# Master Data
/resource    /barang                         # can:barang.view
/resource    /vendor                         # can:vendor.view
/resource    /customer                       # can:customer.view

# Purchasing
/resource    /permintaan                     # can:permintaan.view
/resource    /po                             # can:po.view
/resource    /penerimaan                     # can:penerimaan.view
/resource    /invoice-purchasing             # can:invoice_purchasing.view
/resource    /retur-purchasing               # can:retur_purchasing.view

# Sales
/resource    /so                             # can:so.view
/resource    /do                             # can:do.view
/resource    /invoice-sales                  # can:invoice_sales.view
/resource    /retur-sales                    # can:retur_sales.view
/resource    /kwitansi                       # can:kwitansi.view

# Laporan
GET          /laporan/pembelian              # can:laporan.pembelian
GET          /laporan/penjualan              # can:laporan.penjualan
GET          /laporan/keuangan               # can:laporan.keuangan
```

