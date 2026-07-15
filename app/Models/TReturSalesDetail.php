<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TReturSalesDetail extends Model
{
    protected $table = 't_retur_sales_detail';
    protected $primaryKey = 'id_retur_sales_detail';
    protected $fillable = ['id_retur_sales', 'sku', 'qty_retur', 'alasan_item'];

    public function retur()
    {
        return $this->belongsTo(TReturSales::class, 'id_retur_sales');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}