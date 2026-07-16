<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $table = 'sales_invoices';

    protected $fillable = [
        'delivery_order_id',
        'date',
        'total',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'paid_at',
        'payment_method',
        'status',
        'created_by'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function items()
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}
