<?php

namespace App\Modules\Order\ValidationRules;

use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class serviceOrder implements RulesInterface
{
    private $cart;

    public function __construct($request, $cart)
    {
        $this->request = $request;
        $this->cart = $cart;
    }

    public function validate()
    {
        if (!$this->cart) return false;
        $items = $this->cart->items;
        foreach ($items as $item) {
            if ($item->type != 'normal') return false ;
            if ( !isset($this->request['address_id'])) {
                return new ValidationError("address Id is Required.", 422);
            }
        }
    }
}
