<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TInvoicePurchasingDetail extends Model
{
    protected $table = 't_invoice_purchasing_detail';
    protected $primaryKey = 'id_invoice_purchasing_detail';
    protected $fillable = ['id_invoice_purchasing', 'sku', 'qty', 'harga', 'subtotal'];

    public function invoice()
    {
        return $this->belongsTo(TInvoicePurchasing::class, 'id_invoice_purchasing');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}