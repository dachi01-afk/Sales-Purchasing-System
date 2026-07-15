<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TReturPurchasing extends Model
{
    protected $table = 't_retur_purchasing';
    protected $primaryKey = 'id_retur_purchasing';
    protected $fillable = ['id_penerimaan', 'tanggal', 'alasan', 'created_by'];
    protected $casts = ['tanggal' => 'date'];

    public function details()
    {
        return $this->hasMany(TReturPurchasingDetail::class, 'id_retur_purchasing');
    }

    public function penerimaan()
    {
        return $this->belongsTo(TPenerimaan::class, 'id_penerimaan');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}