<?php

namespace App\Modules\Coupon\ValidationRules;

use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class CouponLimitUsageValid implements RulesInterface
{
    private $coupon;

    public function __construct($coupon, $user)
    {
        $this->coupon = $coupon;
    }

    public function validate()
    {

        if ($this->coupon && $this->coupon->used_count >= $this->coupon->max_limit) {
            return new ValidationError("Coupon is maximum usage ", 423);
        }
    }
}
