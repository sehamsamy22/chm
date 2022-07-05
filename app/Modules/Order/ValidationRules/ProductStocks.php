<?php

namespace App\Modules\Order\ValidationRules;

use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class ProductStocks implements RulesInterface
{
    private $cart;

    public function __construct($cart)
    {
        $this->cart = $cart;
    }

    public function validate()
    {
        if (!$this->cart) return false;
        $items = $this->cart->items;
        foreach ($items as $item) {
            dd($item->pivot->quantity, $item->max_per_order ,$item->stock);
            if ($item->pivot->quantity > $item->max_per_order || $item->pivot->quantity > $item->stock) {
                return new ValidationError("The product with name {$item->name} doesnt have enough stock", 423);
            }
        }
    }
}
