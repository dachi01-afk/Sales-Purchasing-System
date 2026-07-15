<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPermintaan extends Model
{
    protected $table = 't_permintaan';

    protected $primaryKey = 'id_permintaan';

    protected $fillable = [
        'tanggal', 'keterangan', 'status', 'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(TPermintaanDetail::class, 'id_permintaan');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
