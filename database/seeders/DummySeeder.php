<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummySeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $userId = User::first()->id;

            // ==================== PRODUCTS (10) ====================
            $products = [
                ['sku' => 'PRD-001', 'name' => 'Laptop ASUS Vivobook 14', 'standard_price' => 8500000],
                ['sku' => 'PRD-002', 'name' => 'Keyboard Logitech K120', 'standard_price' => 195000],
                ['sku' => 'PRD-003', 'name' => 'Mouse Logitech G102', 'standard_price' => 175000],
                ['sku' => 'PRD-004', 'name' => 'Monitor Samsung 24 inch', 'standard_price' => 2100000],
                ['sku' => 'PRD-005', 'name' => 'RAM Kingston 8GB DDR4', 'standard_price' => 450000],
                ['sku' => 'PRD-006', 'name' => 'SSD Samsung 500GB', 'standard_price' => 750000],
                ['sku' => 'PRD-007', 'name' => 'Headset JBL Tune 510', 'standard_price' => 425000],
                ['sku' => 'PRD-008', 'name' => 'Webcam Logitech C920', 'standard_price' => 875000],
                ['sku' => 'PRD-009', 'name' => 'Printer Epson L3210', 'standard_price' => 3200000],
                ['sku' => 'PRD-010', 'name' => 'UPS APC 600VA', 'standard_price' => 650000],
            ];

            foreach ($products as $p) {
                Product::create($p);
            }

            // ==================== VENDORS (10) ====================
            $vendors = [
                ['name' => 'PT. Mega Komputer', 'phone' => '021-5551234', 'address' => 'Jl. Mangga Dua No. 10, Jakarta'],
                ['name' => 'CV. Tekno Jaya', 'phone' => '021-5552345', 'address' => 'Jl. Pangeran Jayakarta No. 25, Jakarta'],
                ['name' => 'PT. Sinar Elektronik', 'phone' => '021-5553456', 'address' => 'Jl. Glodok Plaza No. 8, Jakarta'],
                ['name' => 'CV. Prima Komputer', 'phone' => '021-5554567', 'address' => 'Jl. Roxy Mas No. 12, Jakarta'],
                ['name' => 'PT. Gemilang Tech', 'phone' => '021-5555678', 'address' => 'Jl. Hayam Wuruk No. 30, Jakarta'],
                ['name' => 'CV. Berkah Komputer', 'phone' => '021-5556789', 'address' => 'Jl. Gajah Mada No. 45, Jakarta'],
                ['name' => 'PT. Cahaya Digital', 'phone' => '021-5557890', 'address' => 'Jl. Kramat Raya No. 18, Jakarta'],
                ['name' => 'CV. Adil Komputer', 'phone' => '021-5558901', 'address' => 'Jl. Salemba Raya No. 22, Jakarta'],
                ['name' => 'PT. Nusantara Tech', 'phone' => '021-5559012', 'address' => 'Jl. Cempaka Putih No. 5, Jakarta'],
                ['name' => 'CV. Sentosa Elektronik', 'phone' => '021-5550123', 'address' => 'Jl. Pemuda No. 33, Jakarta'],
            ];

            foreach ($vendors as $v) {
                Vendor::create($v);
            }

            // ==================== CUSTOMERS (10) ====================
            $customers = [
                ['name' => 'PT. Maju Bersama', 'phone' => '0812-3456-7890', 'address' => 'Jl. Sudirman No. 100, Jakarta'],
                ['name' => 'CV. Sejahtera Abadi', 'phone' => '0813-4567-8901', 'address' => 'Jl. Thamrin No. 55, Jakarta'],
                ['name' => 'PT. Cahaya Makmur', 'phone' => '0821-5678-9012', 'address' => 'Jl. Gatot Subroto No. 70, Jakarta'],
                ['name' => 'CV. Berkah Jaya', 'phone' => '0856-6789-0123', 'address' => 'Jl. Kemang Raya No. 15, Jakarta'],
                ['name' => 'PT. Gemilang Sukses', 'phone' => '0877-7890-1234', 'address' => 'Jl. Senayan No. 40, Jakarta'],
                ['name' => 'CV. Prima Kencana', 'phone' => '0811-8901-2345', 'address' => 'Jl. Kelapa Gading No. 28, Jakarta'],
                ['name' => 'PT. Nusantara Jaya', 'phone' => '0819-9012-3456', 'address' => 'Jl. BSD Raya No. 5, Tangerang'],
                ['name' => 'CV. Adil Bersaudara', 'phone' => '0822-0123-4567', 'address' => 'Jl. Pamulang Raya No. 12, Tangerang'],
                ['name' => 'PT. Sinar Terang', 'phone' => '0852-1234-5678', 'address' => 'Jl. Ciledug Raya No. 8, Tangerang'],
                ['name' => 'CV. Jaya Abadi', 'phone' => '0857-2345-6789', 'address' => 'Jl. Bintaro Raya No. 3, Tangerang'],
            ];

            foreach ($customers as $c) {
                Customer::create($c);
            }

            // ==================== PURCHASE REQUESTS (10) + ITEMS ====================
            $vendorIds = Vendor::pluck('id')->toArray();
            $productSkus = Product::pluck('sku')->toArray();

            for ($i = 1; $i <= 10; $i++) {
                $pr = PurchaseRequest::create([
                    'date' => now()->subDays(rand(1, 60)),
                    'notes' => "Purchase request #{$i}",
                    'status' => 'approved',
                    'created_by' => $userId,
                ]);

                $itemCount = rand(1, 3);
                for ($j = 0; $j < $itemCount; $j++) {
                    PurchaseRequestItem::create([
                        'purchase_request_id' => $pr->id,
                        'sku' => $productSkus[array_rand($productSkus)],
                        'qty' => rand(1, 20),
                        'notes' => null,
                    ]);
                }
            }

            // ==================== PURCHASE ORDERS (10) + ITEMS ====================
            $prIds = PurchaseRequest::pluck('id')->toArray();

            for ($i = 1; $i <= 10; $i++) {
                $po = PurchaseOrder::create([
                    'purchase_request_id' => $prIds[array_rand($prIds)],
                    'vendor_id' => $vendorIds[array_rand($vendorIds)],
                    'date' => now()->subDays(rand(1, 50)),
                    'status' => 'completed',
                    'created_by' => $userId,
                ]);

                $itemCount = rand(1, 3);
                $subtotalAll = 0;
                for ($j = 0; $j < $itemCount; $j++) {
                    $sku = $productSkus[array_rand($productSkus)];
                    $qty = rand(1, 20);
                    $price = Product::find($sku)->standard_price;
                    $subtotal = $qty * $price;
                    $subtotalAll += $subtotal;

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'sku' => $sku,
                        'qty' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            // ==================== GOODS RECEIPTS (10) + ITEMS ====================
            $poIds = PurchaseOrder::pluck('id')->toArray();

            for ($i = 1; $i <= 10; $i++) {
                $poId = $poIds[array_rand($poIds)];
                $gr = GoodsReceipt::create([
                    'purchase_order_id' => $poId,
                    'date' => now()->subDays(rand(1, 40)),
                    'notes' => "Goods receipt #{$i}",
                    'created_by' => $userId,
                ]);

                $poItems = PurchaseOrderItem::where('purchase_order_id', $poId)->get();
                foreach ($poItems as $poItem) {
                    GoodsReceiptItem::create([
                        'goods_receipt_id' => $gr->id,
                        'sku' => $poItem->sku,
                        'qty_received' => $poItem->qty,
                    ]);
                }
            }

            // ==================== PURCHASE INVOICES (10) + ITEMS ====================
            for ($i = 1; $i <= 10; $i++) {
                $poId = $poIds[array_rand($poIds)];
                $pi = PurchaseInvoice::create([
                    'purchase_order_id' => $poId,
                    'date' => now()->subDays(rand(1, 35)),
                    'total' => 0,
                    'status' => 'paid',
                    'created_by' => $userId,
                ]);

                $poItems = PurchaseOrderItem::where('purchase_order_id', $poId)->get();
                $totalAll = 0;
                foreach ($poItems as $poItem) {
                    PurchaseInvoiceItem::create([
                        'purchase_invoice_id' => $pi->id,
                        'sku' => $poItem->sku,
                        'qty' => $poItem->qty,
                        'price' => $poItem->price,
                        'subtotal' => $poItem->subtotal,
                    ]);
                    $totalAll += $poItem->subtotal;
                }
                $pi->update(['total' => $totalAll]);
            }

            // ==================== PURCHASE RETURNS (10) + ITEMS ====================
            $grIds = GoodsReceipt::pluck('id')->toArray();

            for ($i = 1; $i <= 10; $i++) {
                $grId = $grIds[array_rand($grIds)];
                $prReturn = PurchaseReturn::create([
                    'goods_receipt_id' => $grId,
                    'date' => now()->subDays(rand(1, 20)),
                    'reason' => "Return barang #{$i} — kualitas tidak sesuai",
                    'created_by' => $userId,
                ]);

                $grItems = GoodsReceiptItem::where('goods_receipt_id', $grId)->get();
                foreach ($grItems as $grItem) {
                    PurchaseReturnItem::create([
                        'purchase_return_id' => $prReturn->id,
                        'sku' => $grItem->sku,
                        'qty' => rand(1, $grItem->qty_received),
                        'reason' => 'Barang rusak',
                    ]);
                }
            }

            // ==================== SALES ORDERS (10) + ITEMS ====================
            $customerIds = Customer::pluck('id')->toArray();

            for ($i = 1; $i <= 10; $i++) {
                $so = SalesOrder::create([
                    'customer_id' => $customerIds[array_rand($customerIds)],
                    'date' => now()->subDays(rand(1, 50)),
                    'total' => 0,
                    'status' => 'completed',
                    'created_by' => $userId,
                ]);

                $itemCount = rand(1, 3);
                $totalAll = 0;
                for ($j = 0; $j < $itemCount; $j++) {
                    $sku = $productSkus[array_rand($productSkus)];
                    $qty = rand(1, 10);
                    $price = Product::find($sku)->standard_price;
                    $subtotal = $qty * $price;
                    $totalAll += $subtotal;

                    SalesOrderItem::create([
                        'sales_order_id' => $so->id,
                        'sku' => $sku,
                        'qty' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);
                }
                $so->update(['total' => $totalAll]);
            }

            // ==================== DELIVERY ORDERS (10) + ITEMS ====================
            $soIds = SalesOrder::pluck('id')->toArray();

            for ($i = 1; $i <= 10; $i++) {
                $soId = $soIds[array_rand($soIds)];
                $doOrder = DeliveryOrder::create([
                    'sales_order_id' => $soId,
                    'date' => now()->subDays(rand(1, 35)),
                    'shipping_address' => Customer::find(SalesOrder::find($soId)->customer_id)->address,
                    'created_by' => $userId,
                ]);

                $soItems = SalesOrderItem::where('sales_order_id', $soId)->get();
                foreach ($soItems as $soItem) {
                    DeliveryOrderItem::create([
                        'delivery_order_id' => $doOrder->id,
                        'sku' => $soItem->sku,
                        'qty' => $soItem->qty,
                    ]);
                }
            }

            // ==================== SALES INVOICES (10) + ITEMS ====================
            $doIds = DeliveryOrder::pluck('id')->toArray();

            for ($i = 1; $i <= 10; $i++) {
                $doId = $doIds[array_rand($doIds)];
                $si = SalesInvoice::create([
                    'delivery_order_id' => $doId,
                    'date' => now()->subDays(rand(1, 30)),
                    'total' => 0,
                    'status' => 'paid',
                    'created_by' => $userId,
                ]);

                $doItems = DeliveryOrderItem::where('delivery_order_id', $doId)->get();
                $totalAll = 0;
                foreach ($doItems as $doItem) {
                    $product = Product::find($doItem->sku);
                    $price = $product ? $product->standard_price : 100000;
                    $subtotal = $doItem->qty * $price;

                    SalesInvoiceItem::create([
                        'sales_invoice_id' => $si->id,
                        'sku' => $doItem->sku,
                        'qty' => $doItem->qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);
                    $totalAll += $subtotal;
                }
                $si->update(['total' => $totalAll]);
            }

            // ==================== SALES RETURNS (10) + ITEMS ====================
            for ($i = 1; $i <= 10; $i++) {
                $doId = $doIds[array_rand($doIds)];
                $sr = SalesReturn::create([
                    'delivery_order_id' => $doId,
                    'date' => now()->subDays(rand(1, 15)),
                    'reason' => "Return barang #{$i} — tidak sesuai pesanan",
                    'created_by' => $userId,
                ]);

                $doItems = DeliveryOrderItem::where('delivery_order_id', $doId)->get();
                foreach ($doItems as $doItem) {
                    SalesReturnItem::create([
                        'sales_return_id' => $sr->id,
                        'sku' => $doItem->sku,
                        'qty' => rand(1, $doItem->qty),
                        'reason' => 'Barang salah',
                    ]);
                }
            }

            // ==================== RECEIPTS (10) ====================
            $siIds = SalesInvoice::pluck('id')->toArray();

            for ($i = 1; $i <= 10; $i++) {
                $siId = $siIds[array_rand($siIds)];
                $invoice = SalesInvoice::find($siId);
                Receipt::create([
                    'sales_invoice_id' => $siId,
                    'date' => $invoice->date,
                    'amount' => $invoice->total,
                    'notes' => "Pembayaran invoice #{$siId}",
                    'created_by' => $userId,
                ]);
            }

            DB::commit();
            $this->command->info('Dummy data berhasil dibuat! 10 record per tabel.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal membuat dummy data: ' . $e->getMessage());
            throw $e;
        }
    }
}
