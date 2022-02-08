<?php

namespace App\Modules\Coupon\ValidationRules;

use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;
use Carbon\Carbon;

class CouponExpireValid implements RulesInterface
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
        if ($this->coupon && !Carbon::now()->between($this->coupon->start_date,$this->coupon->end_date)) {
            return new ValidationError("Coupon date is  expired ", 423);
        }
    }
}
