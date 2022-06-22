<?php

namespace App\Modules\Order\Transformers;

use App\Http\Resources\Customer\UserResource;
use App\Modules\Address\Transformers\AddressResource;
use App\Modules\Payment\Transformers\PaymentResource;
use App\Modules\Product\Transformers\ProductResource;
use App\Modules\Subscription\Transformers\SubscriptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'total' => $this->total,
            'amount' => $this->amount,
            'unique_id' => $this->unique_id,
            'status' => $this->status,
            'received_name' => $this->received_name,
            'gift_url' => $this->gift_url,
            'status_history' => $this->history->transform(function ($item) {
                return
                    $item->status;
            }),
            'created_At' => $this->created_at->format('d-m-Y'),
            'products' => $this->products->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'image' => $item->image,
                    'quantity' => $item->pivot->quantity,
                    'price' => $item->pivot->price,
                    'product' => new ProductResource($item),
                ];
            }),
            'subscription' => [new SubscriptionResource(($this->type=='custom')?$this->customSubscription:$this->normalSubscription)],

            'payment_method' => new PaymentResource($this->method),
            'user' => new UserResource($this->user),
            'address' => new  AddressResource($this->address),
            'invoice' => new InvoiceResource($this->invoice)
        ];
    }
}
