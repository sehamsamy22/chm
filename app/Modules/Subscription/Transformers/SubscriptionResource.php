<?php

namespace App\Modules\Subscription\Transformers;

use App\Modules\Blog\Entities\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'type' => $this->type,
            'subscriptionType' => new SubscriptionTypeResource($this->subscriptionType),
            'size' =>optional( new SubscriptionSizeResource($this->size)),
            'delivery' => new SubscriptionDeliveryCountResource($this->delivery),
            'wrappingType' => new WrappingTypeResource($this->wrappingType),
            'dayCount' => new SubscriptionDayCountResource($this->dayCount),
            'pickupTime' => $this->time_id,
            'normalSubscription' => new NormalSubscriptionResource($this->normalSubscription),

        ];
    }
}
