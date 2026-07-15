<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TReturSales extends Model
{
    protected $table = 't_retur_sales';
    protected $primaryKey = 'id_retur_sales';
    protected $fillable = ['id_do', 'tanggal', 'alasan', 'created_by'];
    protected $casts = ['tanggal' => 'date'];

    public function details()
    {
        return $this->hasMany(TReturSalesDetail::class, 'id_retur_sales');
    }

    public function do()
    {
        return $this->belongsTo(TDo::class, 'id_do');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}