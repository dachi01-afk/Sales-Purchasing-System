<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TSoDetail extends Model
{
    protected $table = 't_so_detail';
    protected $primaryKey = 'id_so_detail';
    protected $fillable = ['id_so', 'sku', 'qty', 'harga', 'subtotal'];

    public function so()
    {
        return $this->belongsTo(TSo::class, 'id_so');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}