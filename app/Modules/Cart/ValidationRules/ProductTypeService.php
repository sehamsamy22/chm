<?php

namespace App\Modules\Cart\ValidationRules;

use App\Modules\Product\Entities\Product;
use App\Scopes\NormalProductScope;
use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;
use Illuminate\Support\Facades\Auth;

class ProductTypeService implements RulesInterface
{
    private $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function validate()
    {

        $cart = Auth::user()->cart;
        if ($cart) {
            $product = Product::withoutGlobalScope(NormalProductScope::class)->find($this->item['product_id']);
            if ($product->type == 'service' && count($cart->items) != 0) {
                return new ValidationError("sorry,please empty your cart first", 423);
            }
            if ($product->type != 'service' && count($cart->items) != 0) {
                foreach ($cart->items as $item) {
                    if ($item->type == 'service') return new ValidationError("sorry,you cart have service product checkout before", 423);
                }
            }
        }
    }
}
