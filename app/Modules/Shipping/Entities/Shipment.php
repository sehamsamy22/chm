<?php

namespace App\Modules\Shipping\Entities;

use App\Modules\Order\Entities\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Shipment extends Model
{
    use HasFactory;

    protected $fillable = ['shipment_ID', 'label_url', 'shipment_details', 'order_id', 'method_id'];

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'method_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
