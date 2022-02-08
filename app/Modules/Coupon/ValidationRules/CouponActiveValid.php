<?php

namespace App\Modules\Coupon\ValidationRules;

use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class CouponActiveValid implements RulesInterface
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
        if ($this->coupon && $this->coupon->deactivated_at != Null) {
            return new ValidationError("Coupon is deactivated ", 423);
        }
    }
}
