<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPoDetail extends Model
{
    protected $table = 't_po_detail';

    protected $primaryKey = 'id_po_detail';

    protected $fillable = ['id_po', 'sku', 'qty', 'harga', 'subtotal'];

    public function po()
    {
        return $this->belongsTo(TPo::class, 'id_po');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}
