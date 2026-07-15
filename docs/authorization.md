# Authorization — SPM System

## User Akun (Dummy)

| Email | Password | Role |
|---|---|---|
| admin@test.local | password | admin |
| purchasing-staff@test.local | password | purchasing-staff |
| sales-staff@test.local | password | sales-staff |
| finance@test.local | password | finance |
| manager@test.local | password | manager |

## Matrix Permission per Role

| Modul | Permission | Admin | Purchasing | Sales | Finance | Manager |
|---|---|---|---|---|---|---|
| **Barang** | view | ✓ | ✓ | ✓ | ✓ | ✓ |
| | create | ✓ | ✗ | ✗ | ✗ | ✗ |
| | edit | ✓ | ✗ | ✗ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Vendor** | view | ✓ | ✓ | ✗ | ✓ | ✓ |
| | create | ✓ | ✓ | ✗ | ✗ | ✗ |
| | edit | ✓ | ✓ | ✗ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Customer** | view | ✓ | ✗ | ✓ | ✓ | ✓ |
| | create | ✓ | ✗ | ✓ | ✗ | ✗ |
| | edit | ✓ | ✗ | ✓ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Permintaan** | view/create/edit | ✓ | ✓ | ✗ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **PO** | view/create/edit | ✓ | ✓ | ✗ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Penerimaan** | view/create/edit | ✓ | ✓ | ✗ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Invoice Purchasing** | view | ✓ | ✓ | ✗ | ✓ | ✓ |
| | create/edit | ✓ | ✗ | ✗ | ✓ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Retur Purchasing** | view | ✓ | ✓ | ✗ | ✗ | ✓ |
| | create | ✓ | ✓ | ✗ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **SO** | view/create/edit | ✓ | ✗ | ✓ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **DO** | view/create/edit | ✓ | ✗ | ✓ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Invoice Sales** | view | ✓ | ✗ | ✓ | ✓ | ✓ |
| | create/edit | ✓ | ✗ | ✗ | ✓ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Retur Sales** | view | ✓ | ✗ | ✓ | ✗ | ✓ |
| | create | ✓ | ✗ | ✓ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Kwitansi** | view/create/edit | ✓ | ✗ | ✗ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Laporan Pembelian** | view | ✓ | ✗ | ✗ | ✗ | ✓ |
| **Laporan Penjualan** | view | ✓ | ✗ | ✗ | ✗ | ✓ |
| **Laporan Keuangan** | view | ✓ | ✗ | ✗ | ✗ | ✓ |

## Cara Penggunaan

### Di Route (web.php)

Route sudah diproteksi menggunakan middleware `can:`:

```php
Route::middleware(['can:barang.view'])->group(function () {
    Route::resource('barang', BarangController::class);
});
```

Laravel otomatis memetakan:
- `index`/`show` → `{module}.view`
- `create`/`store` → `{module}.create`
- `edit`/`update` → `{module}.edit`
- `destroy` → `{module}.delete`

### Di View (Blade)

Sembunyikan tombol berdasarkan permission:

```blade
@can('barang.edit')
    <a href="{{ route('barang.edit', $b) }}">Edit</a>
@endcan

@can('barang.delete')
    <form action="{{ route('barang.destroy', $b) }}" method="POST">
        @csrf @method('DELETE')
        <button type="submit">Hapus</button>
    </form>
@endcan
```

### Di Controller / Logic Lain

```php
if ($user->can('laporan.keuangan')) {
    // tampilkan data keuangan
}
```

## Catatan

- Permission name mengikuti pattern: `{module}.{action}`
- Module name konsisten dengan route prefix (kecuali invoice/retur yang butuh suffix `_purchasing` / `_sales`)
- Admin mendapat **semua** permission (master + purchasing + sales + finance + laporan)
- Seeder bisa dijalankan kapan saja: `php artisan db:seed --class=RolePermissionSeeder`
