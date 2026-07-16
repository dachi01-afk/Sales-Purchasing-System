<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $table = 'purchase_returns';

    protected $fillable = ['goods_receipt_id', 'date', 'reason', 'created_by'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
