<?php

namespace App\Modules\Cart\ValidationRules;

use App\Modules\Product\Entities\Product;
use App\Scopes\NormalProductScope;
use App\Services\Validation\RulesInterface;
use App\Services\Validation\ValidationError;

class ProductLimitQuantity implements RulesInterface
{
    private $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function validate()
    {
//        if ($this->item && $this->item['quantity'] > Product::withoutGlobalScope(NormalProductScope::class)->find($this->item['product_id'])->max_per_order) {
//            return new ValidationError("quantity is more than maximum per order ", 423);
//        }
    }
}
