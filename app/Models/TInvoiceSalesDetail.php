<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TInvoiceSalesDetail extends Model
{
    protected $table = 't_invoice_sales_detail';
    protected $primaryKey = 'id_invoice_sales_detail';
    protected $fillable = ['id_invoice_sales', 'sku', 'qty', 'harga', 'subtotal'];

    public function invoice()
    {
        return $this->belongsTo(TInvoiceSales::class, 'id_invoice_sales');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}