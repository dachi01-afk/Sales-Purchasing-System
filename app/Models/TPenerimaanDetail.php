<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPenerimaanDetail extends Model
{
    protected $table = 't_penerimaan_detail';
    protected $primaryKey = 'id_penerimaan_detail';
    protected $fillable = ['id_penerimaan', 'sku', 'qty_diterima'];

    public function penerimaan()
    {
        return $this->belongsTo(TPenerimaan::class, 'id_penerimaan');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}