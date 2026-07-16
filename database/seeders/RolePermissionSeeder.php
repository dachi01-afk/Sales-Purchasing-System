<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Master Data
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'vendors.view', 'vendors.create', 'vendors.edit', 'vendors.delete',
            'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
            // Purchasing
            'purchase_requests.view', 'purchase_requests.create', 'purchase_requests.edit', 'purchase_requests.delete',
            'purchase_orders.view', 'purchase_orders.create', 'purchase_orders.edit', 'purchase_orders.delete',
            'goods_receipts.view', 'goods_receipts.create', 'goods_receipts.edit', 'goods_receipts.delete',
            'purchase_invoices.view', 'purchase_invoices.create', 'purchase_invoices.edit', 'purchase_invoices.delete',
            'purchase_returns.view', 'purchase_returns.create', 'purchase_returns.edit', 'purchase_returns.delete',
            // Sales
            'sales_orders.view', 'sales_orders.create', 'sales_orders.edit', 'sales_orders.delete',
            'delivery_orders.view', 'delivery_orders.create', 'delivery_orders.edit', 'delivery_orders.delete',
            'sales_invoices.view', 'sales_invoices.create', 'sales_invoices.edit', 'sales_invoices.delete',
            'sales_returns.view', 'sales_returns.create', 'sales_returns.edit', 'sales_returns.delete',
            // Finance
            'receipts.view', 'receipts.create', 'receipts.edit', 'receipts.delete',
            // Reports
            'reports.purchases', 'reports.sales', 'reports.financial',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo($permissions);

        $purchasing = Role::firstOrCreate(['name' => 'purchasing-staff']);
        $purchasing->givePermissionTo([
            'products.view', 'vendors.view', 'vendors.create', 'vendors.edit',
            'purchase_requests.view', 'purchase_requests.create', 'purchase_requests.edit',
            'purchase_orders.view', 'purchase_orders.create', 'purchase_orders.edit',
            'goods_receipts.view', 'goods_receipts.create', 'goods_receipts.edit',
            'purchase_invoices.view',
            'purchase_returns.view', 'purchase_returns.create',
        ]);

        $sales = Role::firstOrCreate(['name' => 'sales-staff']);
        $sales->givePermissionTo([
            'products.view', 'customers.view', 'customers.create', 'customers.edit',
            'sales_orders.view', 'sales_orders.create', 'sales_orders.edit',
            'delivery_orders.view', 'delivery_orders.create', 'delivery_orders.edit',
            'sales_invoices.view',
            'sales_returns.view', 'sales_returns.create',
        ]);

        $finance = Role::firstOrCreate(['name' => 'finance']);
        $finance->givePermissionTo([
            'products.view', 'vendors.view', 'customers.view',
            'purchase_requests.view', 'purchase_orders.view', 'goods_receipts.view',
            'sales_orders.view', 'delivery_orders.view',
            'purchase_invoices.view', 'purchase_invoices.create', 'purchase_invoices.edit',
            'sales_invoices.view', 'sales_invoices.create', 'sales_invoices.edit',
            'receipts.view', 'receipts.create', 'receipts.edit',
        ]);

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo([
            'products.view', 'vendors.view', 'customers.view',
            'purchase_requests.view', 'purchase_orders.view', 'goods_receipts.view',
            'purchase_invoices.view', 'purchase_returns.view',
            'sales_orders.view', 'delivery_orders.view', 'sales_invoices.view', 'sales_returns.view',
            'receipts.view',
            'reports.purchases', 'reports.sales', 'reports.financial',
        ]);
    }
}
