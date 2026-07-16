# Authorization — SPM System

## User Accounts (Dummy)

| Email | Password | Role |
|---|---|---|
| admin@test.local | password | admin |
| purchasing-staff@test.local | password | purchasing-staff |
| sales-staff@test.local | password | sales-staff |
| finance@test.local | password | finance |
| manager@test.local | password | manager |

## Permission Matrix per Role

| Module | Permission | Admin | Purchasing | Sales | Finance | Manager |
|---|---|---|---|---|---|---|
| **Products** | view | ✓ | ✓ | ✓ | ✓ | ✓ |
| | create | ✓ | ✗ | ✗ | ✗ | ✗ |
| | edit | ✓ | ✗ | ✗ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Vendors** | view | ✓ | ✓ | ✗ | ✓ | ✓ |
| | create | ✓ | ✓ | ✗ | ✗ | ✗ |
| | edit | ✓ | ✓ | ✗ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Customers** | view | ✓ | ✗ | ✓ | ✓ | ✓ |
| | create | ✓ | ✗ | ✓ | ✗ | ✗ |
| | edit | ✓ | ✗ | ✓ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Purchase Requests** | view/create/edit | ✓ | ✓ | ✗ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Purchase Orders** | view/create/edit | ✓ | ✓ | ✗ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Goods Receipts** | view/create/edit | ✓ | ✓ | ✗ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Purchase Invoices** | view | ✓ | ✓ | ✗ | ✓ | ✓ |
| | create/edit | ✓ | ✗ | ✗ | ✓ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Purchase Returns** | view | ✓ | ✓ | ✗ | ✗ | ✓ |
| | create | ✓ | ✓ | ✗ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Sales Orders** | view/create/edit | ✓ | ✗ | ✓ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Delivery Orders** | view/create/edit | ✓ | ✗ | ✓ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Sales Invoices** | view | ✓ | ✗ | ✓ | ✓ | ✓ |
| | create/edit | ✓ | ✗ | ✗ | ✓ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Sales Returns** | view | ✓ | ✗ | ✓ | ✗ | ✓ |
| | create | ✓ | ✗ | ✓ | ✗ | ✗ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Receipts** | view/create/edit | ✓ | ✗ | ✗ | ✓ | ✓ |
| | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| **Purchase Reports** | view | ✓ | ✗ | ✗ | ✗ | ✓ |
| **Sales Reports** | view | ✓ | ✗ | ✗ | ✗ | ✓ |
| **Financial Reports** | view | ✓ | ✗ | ✗ | ✗ | ✓ |

## Usage Guide

### In Routes (web.php)

Routes are protected using the `can:` middleware:

```php
Route::middleware(['can:products.view'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

Laravel automatically maps:
- `index`/`show` → `{module}.view`
- `create`/`store` → `{module}.create`
- `edit`/`update` → `{module}.edit`
- `destroy` → `{module}.delete`

### In Views (Blade)

Hide buttons based on permission:

```blade
@can('products.edit')
    <a href="{{ route('products.edit', $product) }}">Edit</a>
@endcan

@can('products.delete')
    <form action="{{ route('products.destroy', $product) }}" method="POST">
        @csrf @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endcan
```

### In Controller / Logic

```php
if ($user->can('reports.financial')) {
    // show financial data
}
```

## Notes

- Permission names follow the pattern: `{module}.{action}`
- Module names use plural snake_case (e.g., `purchase_orders.view`)
- Admin gets **all** permissions (master + purchasing + sales + finance + reports)
- Run the seeder anytime: `php artisan db:seed --class=RolePermissionSeeder`
