<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TReturPurchasingDetail extends Model
{
    protected $table = 't_retur_purchasing_detail';
    protected $primaryKey = 'id_retur_purchasing_detail';
    protected $fillable = ['id_retur_purchasing', 'sku', 'qty_retur', 'alasan_item'];

    public function retur()
    {
        return $this->belongsTo(TReturPurchasing::class, 'id_retur_purchasing');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}