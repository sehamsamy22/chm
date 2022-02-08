<?php

namespace App\Modules\Coupon\ValidationRules;

use App\Models\User;
use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class CouponUserValid implements RulesInterface
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
        $usersCoupon = $this->coupon->customizations->where('model_type', User::class)->pluck('model_id')->toArray();
        if (count($usersCoupon) && !in_array($this->user->id, $usersCoupon)) {
            return new ValidationError("You cant allow use this coupon,", 423);
        }

    }

}
