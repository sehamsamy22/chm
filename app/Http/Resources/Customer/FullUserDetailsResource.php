<?php

namespace App\Http\Resources\Customer;

use App\Modules\Address\Transformers\AddressResource;
use App\Modules\Cart\Transformers\CartResource;
use App\Modules\Order\Transformers\OrderResource;
use App\Modules\Product\Transformers\ProductResource;
use App\Modules\Subscription\Transformers\SubscriptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FullUserDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'lang' => $this->lang,
            'image' => $this->image,
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at->format('d-m-Y'),
            'orders' => OrderResource::collection($this->orders),
            'addresses' => AddressResource::collection($this->addresses),
            'wishes' => $this->wishes->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'product' => new ProductResource($product),
                ];
            }),
            'compares' => $this->compares->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'product' => new ProductResource($product),
                ];
            }),
            'cart' =>
                $this->cart
                ? $this->cart->items->isEmpty() ?
                    [new SubscriptionResource(($this->cart->type=='custom')?$this->cart->customSubscription:$this->cart->normalSubscription)]
                        : optional(new CartResource($this->cart))->items->transform(function ($item) {
                        return [
                            'id' => $item->id,
                            'price' => $item->pivot->price,
                            'quantity' => $item->pivot->quantity,
                            'product' => new ProductResource($item),
                        ];
                    })
             :[],
            'token' => $this->token,
        ];
    }
}
