<?php

namespace App\Modules\Blog\Transformers;

use App\Modules\Blog\Entities\CustomerOpinion;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
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
            'description' => $request->header('Content-language') ? $this->description : $this->getTranslations('description'),
            'image' => $this->image,
        ];
    }
}
