<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPenerimaan extends Model
{
    protected $table = 't_penerimaan';
    protected $primaryKey = 'id_penerimaan';
    protected $fillable = ['id_po', 'tanggal', 'keterangan', 'created_by'];
    protected $casts = ['tanggal' => 'date'];

    public function details()
    {
        return $this->hasMany(TPenerimaanDetail::class, 'id_penerimaan');
    }

    public function po()
    {
        return $this->belongsTo(TPo::class, 'id_po');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function returPurchasing()
    {
        return $this->hasOne(TReturPurchasing::class, 'id_penerimaan');
    }
}