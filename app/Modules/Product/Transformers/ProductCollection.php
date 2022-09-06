<?php

namespace App\Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource

{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    //    public function withResponse($request, $response)
    //    {
    //        $originalContent = $response->getOriginalContent();
    //        unset($originalContent['links'], $originalContent['meta']);
    //        $response->setData($originalContent);
    //    }


}
