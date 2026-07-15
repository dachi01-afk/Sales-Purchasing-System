<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TInvoiceSales extends Model
{
    protected $table = 't_invoice_sales';
    protected $primaryKey = 'id_invoice_sales';
    protected $fillable = ['id_do', 'tanggal', 'total', 'status', 'created_by'];
    protected $casts = ['tanggal' => 'date', 'total' => 'decimal:2'];

    public function details()
    {
        return $this->hasMany(TInvoiceSalesDetail::class, 'id_invoice_sales');
    }

    public function do()
    {
        return $this->belongsTo(TDo::class, 'id_do');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kwitansi()
    {
        return $this->hasOne(TKwitansi::class, 'id_invoice_sales');
    }
}