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
        $this->baseUrl = 'https://api.xendit.co';
    }

    public function createInvoice(SalesInvoice $invoice): array
    {
        $customer = $invoice->deliveryOrder->salesOrder->customer;
        $items = $invoice->items->map(fn($i) => [
            'name' => $i->product?->name ?? $i->sku,
            'quantity' => $i->qty,
            'price' => (int) $i->price * 100,
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
