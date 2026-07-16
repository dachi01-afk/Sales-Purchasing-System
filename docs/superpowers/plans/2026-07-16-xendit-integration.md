# Xendit Invoice API Integration Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Integrate Xendit Invoice API for automated online payment collection from customers (B2C — QRIS, Virtual Account, E-Wallet)

**Architecture:** New migration adds Xendit tracking columns to `sales_invoices`. A new `XenditService` class handles API communication. A webhook controller receives async payment callbacks. When Xendit confirms `PAID`, system auto-updates invoice status and creates Receipt.

**Tech Stack:** Laravel 13, PHP 8.4, GuzzleHttp (bundled with Laravel), Xendit Invoice API v2

---

## Global Constraints

- No new database tables — only add columns to existing `sales_invoices`
- `.env` for all secrets: `XENDIT_API_KEY`, `XENDIT_WEBHOOK_TOKEN`
- All UI must match existing Flowbite + Tailwind CSS v4 + AlpineJS v3 patterns
- Webhook route is public (no auth) but protected by Xendit callback token verification
- Status enum: `['draft', 'pending_payment', 'paid', 'expired', 'cancelled']`
- Receipt auto-created when payment succeeds (amount = invoice total)
- Sandbox mode first — production requires env change only

---

### Task 0: Fix SalesInvoice & Receipt View Variable Naming (Prerequisite)

**Critical:** The Sales Invoice and Receipt views still reference OLD Indonesian property names that don't match the current English models. These views WILL crash at runtime. Fix them before anything else.

**Files to Modify:**
- `resources/views/sales-invoices/index.blade.php`
- `resources/views/sales-invoices/create.blade.php`
- `resources/views/sales-invoices/edit.blade.php`
- `resources/views/sales-invoices/show.blade.php`
- `resources/views/receipts/index.blade.php`
- `resources/views/receipts/create.blade.php`
- `resources/views/receipts/edit.blade.php`
- `resources/views/receipts/show.blade.php`
- `app/Http/Controllers/SalesInvoiceController.php`

**Mapping Old → New (Model Fields):**
- `$inv->id_invoice_sales` → `$inv->id`
- `$inv->do` → `$inv->deliveryOrder`
- `$inv->do->id_do` → `$inv->deliveryOrder->id`
- `$inv->do->so` → `$inv->deliveryOrder->salesOrder`
- `$inv->do->so->customer->nama_customer` → `$inv->deliveryOrder->salesOrder->customer->name`
- `$inv->tanggal` → `$inv->date`
- `$inv->status === 'lunas'` → `$inv->status === 'paid'`
- `$d->barang->nama_barang` → `$d->product->name`
- `$d->harga` → `$d->price`
- `$inv->details` → `$inv->items`
- `$d->qty_dikirim` → `$d->qty`

**Mapping Controller → View Variables:**
- `SalesInvoiceController@index`: passes `$invoices` → view expects `$salesInvoices` → **change controller to `compact('salesInvoices')`** (rename `$invoices` → `$salesInvoices` in controller)
- `SalesInvoiceController@create`: passes `$deliveryOrders` → view expects `$dos` → **change view to use `$deliveryOrders`**
- `SalesInvoiceController@edit`: passes `$deliveryOrders` → view expects `$dos` → **change view to use `$deliveryOrders`**
- `SalesInvoiceController@show`: passes `$salesInvoice` → view expects `$invoiceSale` → **change view to use `$salesInvoice`**

**Form field name mapping (HTML → Controller validation):**
- `name="id_do"` → `name="delivery_order_id"`
- `name="tanggal"` → `name="date"`
- `name="items[...][harga]"` → `name="items[...][price]"`

**AlpineJS variable mapping:**
- `item.harga` → `item.price`
- `item.nama_barang` → use `item.sku` only (remove `nama_barang` dependency)
- `item.qty_dikirim` → `item.qty`

- [ ] **Step 1: Update SalesInvoiceController `index()`**
  Change `$invoices` to `$salesInvoices` in both the query variable and compact:
  ```
  $salesInvoices = SalesInvoice::with(...)->latest()->paginate(10);
  return view('sales-invoices.index', compact('salesInvoices'));
  ```

- [ ] **Step 2: Fix `sales-invoices/index.blade.php`**
  Replace all old property references:
  ```diff
  - @forelse($salesInvoices as $inv)
  - #{{ $inv->id_invoice_sales }}
  - #{{ $inv->do->id_do }}
  - {{ $inv->do->so->customer->nama_customer }}
  - {{ $inv->tanggal->format('d/m/Y') }}
  - {{ $inv->status === 'lunas' ? '...' : '...' }}
  + @forelse($salesInvoices as $inv)
  + #{{ $inv->id }}
  + #{{ $inv->deliveryOrder->id }}
  + {{ $inv->deliveryOrder->salesOrder->customer->name }}
  + {{ $inv->date->format('d/m/Y') }}
  + {{ $inv->status === 'paid' ? '...' : '...' }}
  ```
  Also update the delete modal label: `$inv->id_invoice_sales` → `$inv->id`

- [ ] **Step 3: Fix `sales-invoices/create.blade.php`**
  ```diff
  - name="id_do"
  + name="delivery_order_id"
  - name="tanggal"
  + name="date"
  - name="items[...][harga]"
  + name="items[...][price]"
  ```
  Fix Alpine data loading:
  ```diff
  - @foreach($dos as $do)
  - <option value="{{ $do->id_do }}" ...
  - data-items='{{ $do->details->map(fn($d) => ['sku' => $d->sku, 'nama_barang' => ..., 'qty_dikirim' => $d->qty_dikirim, 'harga' => ...]) }}'
  - #{{ $do->id_do }} — {{ $do->so->customer->nama_customer }}
  + @foreach($deliveryOrders as $do)
  + <option value="{{ $do->id }}" ...
  + data-items='{{ $do->items->map(fn($d) => ['sku' => $d->sku, 'qty' => $d->qty, 'price' => optional($do->salesOrder->items->firstWhere('sku', $d->sku))->price ?? 0]) }}'
  + #{{ $do->id }} — {{ $do->salesOrder->customer->name }}
  ```
  Fix Alpine defaults:
  ```diff
  - this.items = data ? JSON.parse(data).map(i => ({ ...i, qty: i.qty_dikirim, harga: i.harga || 0 })) : [];
  + this.items = data ? JSON.parse(data).map(i => ({ ...i, qty: i.qty, price: i.price || 0 })) : [];
  - <span x-text="item.sku + ' — ' + item.nama_barang"></span>
  + <span x-text="item.sku"></span>
  - <input type="number" :name="'items[' + index + '][harga]'" x-model="item.harga">
  + <input type="number" :name="'items[' + index + '][price]'" x-model="item.price">
  - <span x-text="'Rp ' + (Number(item.qty) * Number(item.harga)).toLocaleString('id-ID')"></span>
  + <span x-text="'$ ' + (Number(item.qty) * Number(item.price)).toFixed(2)"></span>
  ```
  Fix Alpine total:
  ```diff
  - get total() { return this.items.reduce((sum, i) => sum + (Number(i.qty) * Number(i.harga)), 0) }
  + get total() { return this.items.reduce((sum, i) => sum + (Number(i.qty) * Number(i.price)), 0) }
  - <span x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
  + <span x-text="'$ ' + total.toFixed(2)"></span>
  ```
  Fix status options:
  ```diff
  - <option value="lunas">Paid</option>
  + <option value="paid">Paid</option>
  ```

- [ ] **Step 4: Fix `sales-invoices/edit.blade.php`**
  Apply same fixes as create, plus fix Alpine data initialization:
  ```diff
  - $invoiceSale->id_do
  - $invoiceSale->details->map(fn($d) => ['sku' => $d->sku, 'nama_barang' => $d->barang->nama_barang ?? '', 'qty' => $d->qty, 'harga' => $d->harga])
  + $salesInvoice->delivery_order_id
  + $salesInvoice->items->map(fn($d) => ['sku' => $d->sku, 'qty' => $d->qty, 'price' => $d->price])
  ```
  Fix all references: `$invoiceSale` → `$salesInvoice`, `$invoiceSale->id_invoice_sales` → `$salesInvoice->id`, `$invoiceSale->id_do` → `$salesInvoice->delivery_order_id`
  Fix DO dropdown: `$dos` → `$deliveryOrders`
  Fix all nested property access patterns as in create.

- [ ] **Step 5: Fix `sales-invoices/show.blade.php`**
  ```diff
  - $invoiceSale->id_invoice_sales
  + $salesInvoice->id
  - $invoiceSale->do->id_do
  + $salesInvoice->deliveryOrder->id
  - $invoiceSale->do->so->customer->nama_customer
  + $salesInvoice->deliveryOrder->salesOrder->customer->name
  - $invoiceSale->tanggal
  + $salesInvoice->date
  - $invoiceSale->status === 'lunas'
  + $salesInvoice->status === 'paid'
  - $invoiceSale->details
  + $salesInvoice->items
  - $d->barang->nama_barang
  + $d->product->name
  - $d->harga
  + $d->price
  ```
  Change variable name: `$invoiceSale` → `$salesInvoice`

- [ ] **Step 6: Fix receipt views**
  Apply similar mapping to `resources/views/receipts/*.blade.php` files.

- [ ] **Step 7: Commit**
  ```bash
  git add resources/views/sales-invoices/ resources/views/receipts/ app/Http/Controllers/SalesInvoiceController.php
  git commit -m "fix: correct sales-invoice and receipt view variable names to match English models"
  ```

---

### Task 1: Add Xendit Columns Migration

**Files:**
- Create: `database/migrations/2026_07_16_000001_add_xendit_columns_to_sales_invoices_table.php`

- [ ] **Step 1: Create migration file**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->string('xendit_invoice_id', 100)->nullable()->after('total');
            $table->text('xendit_invoice_url')->nullable()->after('xendit_invoice_id');
            $table->timestamp('paid_at')->nullable()->after('xendit_invoice_url');
            $table->string('payment_method', 50)->nullable()->after('paid_at');
        });

        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->enum('status', ['draft', 'pending_payment', 'paid', 'expired', 'cancelled'])
                ->default('draft')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn(['xendit_invoice_id', 'xendit_invoice_url', 'paid_at', 'payment_method']);
        });

        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->enum('status', ['draft', 'paid'])->default('draft')->change();
        });
    }
};
```

- [ ] **Step 2: Run migration**
  ```bash
  php artisan migrate
  ```

- [ ] **Step 3: Update SalesInvoice model `$fillable` and `$casts`**
  Add to `$fillable`: `'xendit_invoice_id'`, `'xendit_invoice_url'`, `'paid_at'`, `'payment_method'`
  Add to `casts()`:
  ```php
  'paid_at' => 'datetime',
  ```

- [ ] **Step 4: Update SalesInvoiceController validation rules**
  Add `'pending_payment'`, `'expired'`, `'cancelled'` to the `in:` rule for status:
  ```php
  'status' => 'required|in:draft,pending_payment,paid,expired,cancelled',
  ```

- [ ] **Step 5: Commit**
  ```bash
  git add database/migrations/ app/Models/SalesInvoice.php app/Http/Controllers/SalesInvoiceController.php
  git commit -m "feat: add xendit columns to sales_invoices"
  ```

---

### Task 2: Configure Xendit

**Files:**
- Create: `config/xendit.php`
- Modify: `.env`

- [ ] **Step 1: Create config/xendit.php**

```php
<?php

return [
    'api_key' => env('XENDIT_API_KEY'),
    'webhook_token' => env('XENDIT_WEBHOOK_TOKEN'),
    'sandbox' => env('XENDIT_SANDBOX', true),
];
```

- [ ] **Step 2: Add to .env**
  ```
  XENDIT_API_KEY=
  XENDIT_WEBHOOK_TOKEN=
  XENDIT_SANDBOX=true
  ```

- [ ] **Step 3: Register XenditService in composer autoload or create the directory**
  Create `app/Services/` directory.

- [ ] **Step 4: Commit**
  ```bash
  git add config/xendit.php
  git commit -m "feat: add xendit configuration"
  ```

---

### Task 3: Create XenditService

**Files:**
- Create: `app/Services/XenditService.php`

**Interfaces:**
- `XenditService::createInvoice(SalesInvoice $invoice): array` — returns `['id' => '...', 'invoice_url' => '...', ...]`
- `XenditService::getInvoice(string $xenditId): array` — returns full Xendit invoice object

- [ ] **Step 1: Create `app/Services/XenditService.php`**

```php
<?php

namespace App\Services;

use App\Models\SalesInvoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('xendit.api_key');
        $this->baseUrl = config('xendit.sandbox')
            ? 'https://api.xendit.co'
            : 'https://api.xendit.co'; // same base URL for prod, auth differs
    }

    public function createInvoice(SalesInvoice $invoice): array
    {
        $customer = $invoice->deliveryOrder->salesOrder->customer;
        $items = $invoice->items->map(fn($i) => [
            'name' => $i->product?->name ?? $i->sku,
            'quantity' => $i->qty,
            'price' => (int) $i->price * 100, // Xendit uses cents
            'category' => 'goods',
        ])->toArray();

        $payload = [
            'external_id' => 'SPM-INV-' . $invoice->id . '-' . time(),
            'amount' => (int) $invoice->total * 100,
            'description' => 'Payment for Sales Invoice #' . $invoice->id,
            'customer' => [
                'given_names' => $customer->name,
                'email' => $customer->email ?? '',
            ],
            'customer_notification_preference' => [
                'invoice_paid' => ['email', 'whatsapp'],
            ],
            'success_redirect_url' => url('/sales-invoices/' . $invoice->id),
            'failure_redirect_url' => url('/sales-invoices/' . $invoice->id),
            'items' => $items,
            'fees' => [],
        ];

        $response = Http::withBasicAuth($this->apiKey, '')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->baseUrl . '/v2/invoices', $payload);

        if ($response->failed()) {
            Log::error('Xendit createInvoice failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Xendit API error: ' . $response->body());
        }

        return $response->json();
    }

    public function getInvoice(string $xenditId): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->get($this->baseUrl . '/v2/invoices/' . $xenditId);

        if ($response->failed()) {
            Log::error('Xendit getInvoice failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Xendit API error: ' . $response->body());
        }

        return $response->json();
    }
}
```

- [ ] **Step 2: Register `app/Services` in composer.json autoload if needed**
  Laravel will auto-discover `app/Services/XenditService.php` via PSR-4 autoloading (since `app/` is mapped to `App\`).

- [ ] **Step 3: Commit**
  ```bash
  git add app/Services/
  git commit -m "feat: add XenditService for invoice API communication"
  ```

---

### Task 4: Create Webhook Controller & Route

**Files:**
- Create: `app/Http/Controllers/Api/XenditWebhookController.php`
- Modify: `routes/web.php` (or create `routes/api.php`)

- [ ] **Step 1: Create `app/Http/Controllers/Api/XenditWebhookController.php`**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $token = $request->header('x-callback-token');
        $expected = config('xendit.webhook_token');

        if (!$token || $token !== $expected) {
            Log::warning('Xendit webhook: invalid callback token');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? '';
        $data = $payload['data'] ?? [];

        Log::info('Xendit webhook received', ['event' => $event, 'external_id' => $data['external_id'] ?? '']);

        if ($event !== 'invoice.paid') {
            return response()->json(['status' => 'ignored']);
        }

        $externalId = $data['external_id'] ?? '';
        // Extract invoice ID from external_id: "SPM-INV-{id}-{timestamp}"
        preg_match('/^SPM-INV-(\d+)-/', $externalId, $matches);
        $invoiceId = $matches[1] ?? null;

        if (!$invoiceId) {
            Log::error('Xendit webhook: could not parse invoice ID from external_id', ['external_id' => $externalId]);
            return response()->json(['error' => 'Invalid external_id'], 400);
        }

        $invoice = SalesInvoice::find($invoiceId);
        if (!$invoice) {
            Log::error('Xendit webhook: invoice not found', ['id' => $invoiceId]);
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        if ($invoice->status === 'paid') {
            return response()->json(['status' => 'already_paid']);
        }

        $paidAmount = ($data['paid_amount'] ?? $data['amount'] ?? 0) / 100;
        $paymentMethod = $data['payment_method'] ?? $data['payment_channel'] ?? null;

        \DB::transaction(function () use ($invoice, $paidAmount, $paymentMethod, $data) {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $paymentMethod,
            ]);

            Receipt::create([
                'sales_invoice_id' => $invoice->id,
                'date' => now()->toDateString(),
                'amount' => $paidAmount,
                'notes' => 'Auto-receipt via Xendit (ID: ' . ($data['id'] ?? '') . ')',
                'created_by' => 1, // system user
            ]);
        });

        return response()->json(['status' => 'ok']);
    }
}
```

- [ ] **Step 2: Create `routes/api.php`**

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\XenditWebhookController;

Route::post('xendit/webhook', [XenditWebhookController::class, 'handle']);
```

- [ ] **Step 3: Exclude webhook route from CSRF**

  Open `app/Http/Middleware/VerifyCsrfToken.php` and add to `$except` array:

  ```php
  protected $except = [
      'api/xendit/webhook',
  ];
  ```

- [ ] **Step 4: Commit**
  ```bash
  git add app/Http/Controllers/Api/ routes/api.php app/Http/Middleware/VerifyCsrfToken.php
  git commit -m "feat: add xendit webhook endpoint"
  ```

---

### Task 5: Send Payment Link Action

**Files:**
- Modify: `app/Http/Controllers/SalesInvoiceController.php`
- Modify: `resources/views/sales-invoices/index.blade.php`
- Modify: `resources/views/sales-invoices/show.blade.php`

- [ ] **Step 1: Add `sendPaymentLink` method to SalesInvoiceController**

```php
use App\Services\XenditService;

public function sendPaymentLink(SalesInvoice $salesInvoice)
{
    if ($salesInvoice->status !== 'draft') {
        return back()->with('error', 'Only draft invoices can be sent for payment');
    }

    try {
        $service = app(XenditService::class);
        $result = $service->createInvoice($salesInvoice);

        $salesInvoice->update([
            'xendit_invoice_id' => $result['id'],
            'xendit_invoice_url' => $result['invoice_url'],
            'status' => 'pending_payment',
        ]);

        return back()->with('success', 'Payment link created successfully');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to create payment link: ' . $e->getMessage());
    }
}
```

- [ ] **Step 2: Add route to web.php**

```php
Route::post('sales-invoices/{sales_invoice}/send-payment-link', [SalesInvoiceController::class, 'sendPaymentLink'])
    ->name('sales-invoices.send-payment-link')
    ->middleware('can:sales_invoices.edit');
```

  Place this BEFORE the resource route for sales-invoices, or replace the resource with explicit routes.

- [ ] **Step 3: Add button to `sales-invoices/index.blade.php`**
  In the Actions column, after the Edit button:
  ```blade
  @can('sales_invoices.edit')
  @if($inv->status === 'draft')
  <form action="{{ route('sales-invoices.send-payment-link', $inv) }}" method="POST" class="inline">
      @csrf
      <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-1.5">
          Send Link
      </button>
  </form>
  @endif
  @endcan
  ```

- [ ] **Step 4: Add Xendit URL display to `sales-invoices/index.blade.php`**
  Add a new column between Total and Status (or after Status):
  ```blade
  <th class="px-6 py-3">Payment Link</th>
  ```
  ```blade
  <td class="px-6 py-4">
      @if($inv->xendit_invoice_url)
      <a href="{{ $inv->xendit_invoice_url }}" target="_blank" class="text-blue-600 hover:underline text-xs">
          Open Link
      </a>
      @else
      <span class="text-gray-400">--</span>
      @endif
  </td>
  ```

- [ ] **Step 5: Add button to `sales-invoices/show.blade.php`**
  Add after the Back button:
  ```blade
  @can('sales_invoices.edit')
  @if($salesInvoice->status === 'draft')
  <form action="{{ route('sales-invoices.send-payment-link', $salesInvoice) }}" method="POST" class="inline">
      @csrf
      <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
          Send Payment Link
      </button>
  </form>
  @endif
  @endcan

  @if($salesInvoice->xendit_invoice_url)
  <a href="{{ $salesInvoice->xendit_invoice_url }}" target="_blank" class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
      Open Payment Page
  </a>
  @endif
  ```

- [ ] **Step 6: Add status 'pending_payment' badge styling in index + show**
  Add a new color for `pending_payment`:
  ```blade
  {{ $inv->status === 'pending_payment' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : '' }}
  {{ $inv->status === 'expired' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}
  ```

- [ ] **Step 7: Commit**
  ```bash
  git add app/Http/Controllers/SalesInvoiceController.php routes/web.php resources/views/sales-invoices/
  git commit -m "feat: add send payment link button to sales invoices"
  ```

---

### Task 6: Update Dashboard for New Statuses

**Files:**
- Modify: `app/Http/Controllers/DashboardController.php`

- [ ] **Step 1: Update unpaid invoice query to include pending_payment
  In `DashboardController@index`, the `$unpaidInvoices` currently counts `draft` only. Update to include `pending_payment`:

  ```php
  $unpaidInvoices = SalesInvoice::whereIn('status', ['draft', 'pending_payment'])->count();
  ```

- [ ] **Step 2: Commit**
  ```bash
  git add app/Http/Controllers/DashboardController.php
  git commit -m "fix: include pending_payment invoices in unpaid count"
  ```

---

### Task 7: Testing with Xendit Sandbox

- [ ] **Step 1: Create Xendit sandbox account**
  - Go to https://dashboard.xendit.co/register
  - Select "Development" environment
  - Get API Key from Settings → API Keys
  - Get Webhook Token from Settings → Webhooks

- [ ] **Step 2: Configure .env**
  ```
  XENDIT_API_KEY=xnd_development_xxxxxxxxxx
  XENDIT_WEBHOOK_TOKEN=your_webhook_verification_token
  XENDIT_SANDBOX=true
  ```

- [ ] **Step 3: Expose localhost for webhook testing**
  Use `ngrok` or similar:
  ```bash
  ngrok http 8000
  ```
  Copy the HTTPS URL (e.g., `https://abc123.ngrok.io`).

- [ ] **Step 4: Configure webhook in Xendit Dashboard**
  - Settings → Webhooks → Add Webhook
  - URL: `https://abc123.ngrok.io/api/xendit/webhook`
  - Events: `invoice.paid`
  - Save the callback token

- [ ] **Step 5: Test the flow**
  1. Login as admin/finance
  2. Go to Sales Invoices → Create a new invoice (status: draft)
  3. Click "Send Payment Link" button
  4. Verify: status changes to `pending_payment`, xendit URL appears
  5. Open the payment URL — you'll see Xendit sandbox payment page
  6. Click "Bayar" (simulate payment) — Xendit will show success
  7. Check: webhook is received, invoice status changes to `paid`, Receipt auto-created

- [ ] **Step 6: Run `php artisan migrate:fresh --seed` to reset and test from clean state**

- [ ] **Step 7: Commit final version**
  ```bash
  git add -A
  git commit -m "feat: complete xendit integration"
  git push
  ```

---

## Notes for Executor

1. **Xendit PHP SDK not required** — we use Laravel's built-in `Http` facade (Guzzle). Simpler and fewer dependencies.
2. **CSRF exemption** — webhook routes must be excluded from CSRF protection since Xendit sends raw POST requests.
3. **Status transition rules:**
   - `draft` → `pending_payment` (when payment link sent)
   - `pending_payment` → `paid` (webhook received)
   - `pending_payment` → `expired` (webhook received or manual)
   - Any status → `cancelled` (manual — for cancelled orders)
4. **System user for auto-receipt:** Using `created_by = 1` (admin). If you create a dedicated system user, update this reference.
5. **View fixes in Task 0 are critical** — the views WILL crash if not fixed first. Test each view after fixing by navigating to it.

---

## Self-Review Checklist

- [ ] Spec coverage: All design sections covered (data model, service, webhook, UI, config)
- [ ] No placeholders: All code is complete, no TBD/TODO
- [ ] File paths: All paths are exact and absolute
- [ ] Type consistency: `XenditService::createInvoice()` takes `SalesInvoice`, returns `array`
- [ ] Command examples: Every command is exact and runnable
- [ ] Migration: `change()` method for enum requires `doctrine/dbal` — add `composer require doctrine/dbal` step if needed, or use raw SQL
