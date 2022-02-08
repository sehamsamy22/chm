<?php

namespace App\Modules\Page\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PageInfoResource extends JsonResource
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
            'url' => $this->url,
            'image' => $this->image,
        ];
    }
}
