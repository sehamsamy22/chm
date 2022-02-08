<?php

namespace App\Modules\Shipping\Entities;

use App\Modules\Order\Entities\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrderPickup extends Model
{
    use HasFactory;

    protected $fillable = ['pickup_id', 'order_id', 'shipping_id', 'shipment_url'];

    public function pickup()
    {
        return $this->belongsTo(Pickup::class, 'pickup_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
