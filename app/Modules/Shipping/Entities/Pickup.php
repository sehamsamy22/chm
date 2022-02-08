<?php

namespace App\Modules\Shipping\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pickup extends Model
{
    use HasFactory;

    protected $fillable = ['method_id', 'pickup_time', 'status', 'shipping_id', 'shipping_guid'];

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'method_id');
    }
}
