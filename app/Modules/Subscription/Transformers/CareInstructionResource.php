<?php

namespace App\Modules\Subscription\Transformers;

use App\Modules\Blog\Entities\Blog;
use App\Modules\Product\Transformers\BrandResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CareInstructionResource extends JsonResource
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
            'title' => $request->header('Content-language') ? $this->title : $this->getTranslations('title'),
            'image' => $this->image,
        ];
    }
}
