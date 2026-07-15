<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TSo extends Model
{
    protected $table = 't_so';
    protected $primaryKey = 'id_so';
    protected $fillable = ['id_customer', 'tanggal', 'total', 'status', 'created_by'];
    protected $casts = ['tanggal' => 'date', 'total' => 'decimal:2'];

    public function details()
    {
        return $this->hasMany(TSoDetail::class, 'id_so');
    }

    public function customer()
    {
        return $this->belongsTo(MCustomer::class, 'id_customer');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function do()
    {
        return $this->hasOne(TDo::class, 'id_so');
    }
}