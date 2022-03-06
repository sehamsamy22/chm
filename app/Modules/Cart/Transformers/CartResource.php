<?php

namespace App\Modules\Cart\Transformers;

use App\Http\Resources\Customer\UserResource;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Payment\Transformers\PaymentResource;
use App\Modules\Product\Transformers\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'payment_methods' => PaymentResource::collection(PaymentMethod::all()),
            'items' => $this->items->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'price' =>  $item->pivot->price,
                    'quantity' => $item->pivot->quantity,
                    'total' => $item->pivot->price,
                    'product' => new ProductResource($item),
                ];
            }),
        ];
    }
}
