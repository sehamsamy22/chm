<?php

namespace App\Http\Resources;

use App\Modules\Blog\Entities\CustomerOpinion;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOpinionResource extends JsonResource
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
            'description' => $request->header('Content-language') ? $this->description : $this->getTranslations('description'),
            'image' => $this->image,
        ];
    }
}
