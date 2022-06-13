<?php

namespace App\Modules\Subscription\Transformers;

use App\Modules\Blog\Entities\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionTypeResource extends JsonResource
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
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name'),

        ];
    }
}
