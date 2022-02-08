<?php

namespace App\Modules\Coupon\ValidationRules;


use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class CouponUserExists implements RulesInterface
{
    private $coupon;
    private $user;

    public function __construct($coupon, $user)
    {
        $this->coupon = $coupon;
        $this->user = $user;
    }

    public function validate()
    {
        $couponOrders = $this->coupon->couponOrder()->with('orders')->get();
        if ($couponOrders->count()) {
            $usersIds = $couponOrders->pluck('orders.*.user_id')->first();
            if (in_array($this->user->id, $usersIds)) {
                return new ValidationError("you already use this coupon", 423);
            }
        }
    }
}
