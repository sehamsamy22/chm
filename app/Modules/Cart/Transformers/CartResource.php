<?php

namespace App\Modules\Cart\Transformers;

use App\Http\Resources\Customer\UserResource;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Payment\Transformers\PaymentResource;
use App\Modules\Product\Transformers\ProductResource;
use App\Modules\Subscription\Transformers\SubscriptionResource;
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
//        dd($this->subscription);
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'payment_methods' => PaymentResource::collection(PaymentMethod::all()),
            'items' => $this->items->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'price' => round($item->pivot->price,2) ,
                    'quantity' => $item->pivot->quantity,
                    'total' => round($item->pivot->price,2),
                    'product' => new ProductResource($item),
                    'additional_products'=> $this->getItemAdditions($item),

                ];
            }),
            'subscription' => new SubscriptionResource(($this->type=='custom')?$this->customSubscription:$this->normalSubscription),
            'subscriptionItems' => $this->subscriptionItems->transform(function ($subscriptionitem) use ($request) {
                return [
                    'id' => $subscriptionitem->id,
                    'name' => $request->header('Content-language') ? $subscriptionitem->item->name : $subscriptionitem->item->getTranslations('name'),
                    'image' => $subscriptionitem->item->image,

                ];
            }),
        ];
    }
}
