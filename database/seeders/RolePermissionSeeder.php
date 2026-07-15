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
            // Master
            'barang.view', 'barang.create', 'barang.edit', 'barang.delete',
            'vendor.view', 'vendor.create', 'vendor.edit', 'vendor.delete',
            'customer.view', 'customer.create', 'customer.edit', 'customer.delete',
            // Purchasing
            'permintaan.view', 'permintaan.create', 'permintaan.edit', 'permintaan.delete',
            'po.view', 'po.create', 'po.edit', 'po.delete',
            'penerimaan.view', 'penerimaan.create', 'penerimaan.edit', 'penerimaan.delete',
            'invoice_purchasing.view', 'invoice_purchasing.create', 'invoice_purchasing.edit', 'invoice_purchasing.delete',
            'retur_purchasing.view', 'retur_purchasing.create', 'retur_purchasing.edit', 'retur_purchasing.delete',
            // Sales
            'so.view', 'so.create', 'so.edit', 'so.delete',
            'do.view', 'do.create', 'do.edit', 'do.delete',
            'invoice_sales.view', 'invoice_sales.create', 'invoice_sales.edit', 'invoice_sales.delete',
            'retur_sales.view', 'retur_sales.create', 'retur_sales.edit', 'retur_sales.delete',
            // Finance
            'kwitansi.view', 'kwitansi.create', 'kwitansi.edit', 'kwitansi.delete',
            // Reports
            'laporan.pembelian', 'laporan.penjualan', 'laporan.keuangan',
        ];


        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }
        

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo($permissions);

        $purchasing = Role::firstOrCreate(['name' => 'purchasing-staff']);
        $purchasing->givePermissionTo([
            'barang.view', 'vendor.view', 'vendor.create', 'vendor.edit',
            'permintaan.view', 'permintaan.create', 'permintaan.edit',
            'po.view', 'po.create', 'po.edit',
            'penerimaan.view', 'penerimaan.create', 'penerimaan.edit',
            'invoice_purchasing.view',
            'retur_purchasing.view', 'retur_purchasing.create',
        ]);

        $sales = Role::firstOrCreate(['name' => 'sales-staff']);
        $sales->givePermissionTo([
            'barang.view', 'customer.view', 'customer.create', 'customer.edit',
            'so.view', 'so.create', 'so.edit',
            'do.view', 'do.create', 'do.edit',
            'invoice_sales.view',
            'retur_sales.view', 'retur_sales.create',
        ]);

        $finance = Role::firstOrCreate(['name' => 'finance']);
        $finance->givePermissionTo([
            'barang.view', 'vendor.view', 'customer.view',
            'permintaan.view', 'po.view', 'penerimaan.view',
            'so.view', 'do.view',
            'invoice_purchasing.view', 'invoice_purchasing.create', 'invoice_purchasing.edit',
            'invoice_sales.view', 'invoice_sales.create', 'invoice_sales.edit',
            'kwitansi.view', 'kwitansi.create', 'kwitansi.edit',
        ]);

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo([
            'barang.view', 'vendor.view', 'customer.view',
            'permintaan.view', 'po.view', 'penerimaan.view',
            'invoice_purchasing.view', 'retur_purchasing.view',
            'so.view', 'do.view', 'invoice_sales.view', 'retur_sales.view',
            'kwitansi.view',
            'laporan.pembelian', 'laporan.penjualan', 'laporan.keuangan',
        ]);
    }
}