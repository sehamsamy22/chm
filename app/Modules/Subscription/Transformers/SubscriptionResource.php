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
            'type' => new SubscriptionTypeResource($this->type),
            'size' => new SubscriptionSizeResource($this->size),
            'delivery' => new SubscriptionDeliveryCountResource($this->delivery),
            'wrappingType' => new WrappingTypeResource($this->wrappingType),
            'dayCount' => new SubscriptionDayCountResource($this->dayCount),
            'pickupTime' => $this->time_id,
        ];
    }
}
