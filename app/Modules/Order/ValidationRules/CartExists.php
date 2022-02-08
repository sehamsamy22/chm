<?php

namespace App\Modules\Order\ValidationRules;

use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class CartExists implements RulesInterface
{
    private $coupon;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function validate()
    {
        if (!$this->user->cart) {
            return new ValidationError("Your cart is empty ", 423);
        }
    }
}
