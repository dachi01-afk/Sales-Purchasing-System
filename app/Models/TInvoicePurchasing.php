<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TInvoicePurchasing extends Model
{
    protected $table = 't_invoice_purchasing';
    protected $primaryKey = 'id_invoice_purchasing';
    protected $fillable = ['id_po', 'tanggal', 'total', 'status', 'created_by'];
    protected $casts = ['tanggal' => 'date', 'total' => 'decimal:2'];

    public function details()
    {
        return $this->hasMany(TInvoicePurchasingDetail::class, 'id_invoice_purchasing');
    }

    public function po()
    {
        return $this->belongsTo(TPo::class, 'id_po');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}