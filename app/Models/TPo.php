<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPo extends Model
{
    protected $table = 't_po';

    protected $primaryKey = 'id_po';

    protected $fillable = ['id_permintaan', 'id_vendor', 'tanggal', 'status', 'created_by'];

    protected $casts = ['tanggal' => 'date'];

    public function details()
    {
        return $this->hasMany(TPoDetail::class, 'id_po');
    }

    public function permintaan()
    {
        return $this->belongsTo(TPermintaan::class, 'id_permintaan');
    }

    public function vendor()
    {
        return $this->belongsTo(MVendor::class, 'id_vendor');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoicePurchasing()
    {
        return $this->hasOne(TInvoicePurchasing::class, 'id_po');
    }

    public function penerimaan()
    {
        return $this->hasMany(TPenerimaan::class, 'id_po');
    }
}
