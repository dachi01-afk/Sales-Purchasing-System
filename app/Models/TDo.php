<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TDo extends Model
{
    protected $table = 't_do';
    protected $primaryKey = 'id_do';
    protected $fillable = ['id_so', 'tanggal', 'alamat_pengiriman', 'created_by'];
    protected $casts = ['tanggal' => 'date'];

    public function details()
    {
        return $this->hasMany(TDoDetail::class, 'id_do');
    }

    public function so()
    {
        return $this->belongsTo(TSo::class, 'id_so');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoiceSales()
    {
        return $this->hasOne(TInvoiceSales::class, 'id_do');
    }

    public function returSales()
    {
        return $this->hasOne(TReturSales::class, 'id_do');
    }
}