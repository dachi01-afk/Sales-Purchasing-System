<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $table = 'delivery_orders';

    protected $fillable = ['sales_order_id', 'date', 'shipping_address', 'created_by'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function items()
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function salesInvoice()
    {
        return $this->hasOne(SalesInvoice::class);
    }

    public function salesReturn()
    {
        return $this->hasOne(SalesReturn::class);
    }
}
