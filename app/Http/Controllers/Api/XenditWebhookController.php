<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
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
                'created_by' => 1,
            ]);
        });

        return response()->json(['status' => 'ok']);
    }
}
