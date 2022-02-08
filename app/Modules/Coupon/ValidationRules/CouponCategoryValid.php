<?php

namespace App\Modules\Coupon\ValidationRules;

use App\Modules\Category\Entities\Category;
use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class CouponCategoryValid implements RulesInterface
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
        $couponCategories = $this->coupon->customizations->where('model_type', Category::class)->pluck('model_id')->toArray();
        if (count($couponCategories)) {
            $userCartCategories = $this->user->cart->items()->groupBy('category_id')->pluck('category_id')->toArray();
            $matched_categories = array_intersect($couponCategories, $userCartCategories);
            if (!count($matched_categories)) {
                return new ValidationError("You cant use this coupon,categories ", 423);
            }
        }
    }

}
