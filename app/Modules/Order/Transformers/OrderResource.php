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
            'massage' =>$this->massage,
            'service_product_details' =>$this->service_product_details,
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
             'dayCount' =>   ($this->day_count_id)? $request->header('Content-language') ? $this->dayCount->count : $this->dayCount->getTranslations('count') : '',
             'deliveryCount' =>   ($this->delivery_id)?  $this->delivery->count  : '',

            'type'=>$this->type,
            'subscription' => ($this->type!='items')?[new SubscriptionResource(($this->type=='custom')?$this->customSubscription:$this->normalSubscription)]:[],
            'subscriptionItems' => $this->subscriptionItems->transform(function ($subscriptionitem) use ($request) {
                return [
                    'id' => $subscriptionitem->id,
                    'name' => $request->header('Content-language') ? $subscriptionitem->item->name : $subscriptionitem->item->getTranslations('name'),
                    'image' => $subscriptionitem->item->image,

                ];
            }),
            'payment_method' => new PaymentResource($this->method),
            'user' => new UserResource($this->user),
            'address' => new  AddressResource($this->address),
            'invoice' => new InvoiceResource($this->invoice)
        ];
    }
}
