<?php

namespace App\Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model

{

    protected $fillable = ['order_id', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
