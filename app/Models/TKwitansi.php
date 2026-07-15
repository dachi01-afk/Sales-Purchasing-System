<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TKwitansi extends Model
{
    protected $table = 't_kwitansi';
    protected $primaryKey = 'id_kwitansi';
    protected $fillable = ['id_invoice_sales', 'tanggal', 'jumlah', 'keterangan', 'created_by'];
    protected $casts = ['tanggal' => 'date', 'jumlah' => 'decimal:2'];

    public function invoiceSales()
    {
        return $this->belongsTo(TInvoiceSales::class, 'id_invoice_sales');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}