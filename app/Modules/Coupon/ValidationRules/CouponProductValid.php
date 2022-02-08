<?php

namespace App\Modules\Coupon\ValidationRules;

use App\Modules\Product\Entities\Product;
use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class CouponProductValid implements RulesInterface
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
        $couponProducts = $this->coupon->customizations->where('model_type', Product::class)->pluck('model_id')->toArray();
        if (count($couponProducts) && $this->user->cart) {
            $userCartProducts = $this->user->cart->items->pluck('id')->toArray();
            $matched_products = array_intersect($couponProducts, $userCartProducts);
            if (!count($matched_products)) {
                return new ValidationError("You cant use this coupon,products ", 423);
            }
        }
    }

}
