<?php

namespace App\Modules\Category\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
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
            'input_type' => $this->input_type,
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name') ,
//           'parent_id'=>$this->parent_id,

        ];
    }
}
