<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TPermintaan;
use App\Models\MBarang;

class TPermintaanDetail extends Model
{
    protected $table = 't_permintaan_detail';

    protected $primaryKey = 'id_permintaan_detail';

    protected $fillable = [
        'id_permintaan', 'sku', 'qty', 'keterangan',
    ];

    public function permintaan()
    {
        return $this->belongsTo(TPermintaan::class, 'id_permintaan');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}
