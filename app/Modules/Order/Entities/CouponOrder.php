<?php

namespace App\Modules\Order\Entities;

use App\Modules\Coupon\Entities\Coupon;
use Illuminate\Database\Eloquent\Model;

class CouponOrder extends Model
{

    protected $fillable = ['order_id', 'coupon_id'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
