<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    protected $table = 'goods_receipts';

    protected $fillable = ['purchase_order_id', 'date', 'notes', 'created_by'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function items()
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function purchaseReturn()
    {
        return $this->hasOne(PurchaseReturn::class);
    }
}
