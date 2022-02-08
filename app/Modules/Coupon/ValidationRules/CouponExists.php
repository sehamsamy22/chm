<?php

namespace App\Modules\Coupon\ValidationRules;

use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class CouponExists implements RulesInterface
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
        if(!$this->coupon) {
            return new ValidationError("Coupon_doesnt_exists", 423);
        }
    }
}
