<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TDoDetail extends Model
{
    protected $table = 't_do_detail';
    protected $primaryKey = 'id_do_detail';
    protected $fillable = ['id_do', 'sku', 'qty_dikirim'];

    public function do()
    {
        return $this->belongsTo(TDo::class, 'id_do');
    }

    public function barang()
    {
        return $this->belongsTo(MBarang::class, 'sku', 'sku');
    }
}