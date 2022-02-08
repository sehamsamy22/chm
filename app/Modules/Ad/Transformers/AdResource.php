<?php

namespace App\Modules\Ad\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

//        if ($this->model_type == 'App\\Modules\\Product\\Entities\\Product') $type = 'product';
//        if ($this->model_type == 'App\\Modules\\Product\\Entities\\Lists') $type = 'Lists';
//        if ($this->model_type == 'App\\Modules\\Category\\Entities\\Category') $type = 'category';
        return [
            'id' => $this->id,
            'image' => $this->image,
            'order' => $this->order,
            'external_url' => $this->external_url,
            'start_date' => $this->start_date,
            'expired_at' => $this->expired_at,
            'location_id' => $this->location->name ?? '',
//            'model_type' => $type,
            'title' => $request->header('Content-language') ? $this->title : $this->getTranslations('title'),
            'description' => $request->header('Content-language') ? $this->description : $this->getTranslations('description'),

//            'model' => $this->model

        ];
    }
}
