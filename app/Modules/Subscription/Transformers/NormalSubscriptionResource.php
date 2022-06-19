<?php

namespace App\Modules\Subscription\Transformers;

use App\Modules\Blog\Entities\Blog;
use App\Modules\Product\Transformers\BrandResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NormalSubscriptionResource extends JsonResource
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
            'name' => $request->header('Content-language') ? $this->title : $this->getTranslations('name'),
            'description' => $request->header('Content-language') ? $this->description : $this->getTranslations('description'),
            'price' => $this->price,
            'image' => $this->image,


        ];
    }
}
