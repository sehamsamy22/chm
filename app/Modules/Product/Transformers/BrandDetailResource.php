<?php

namespace App\Modules\Product\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        $products = collect($this->products);
        return [
            'id' => $this->id,
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name') ?? '',
            'image' => $this->image,
            'logo' => $this->logo,
           "products" => ProductResource::collection($this->products),
            "maxProductPrice" => (!$products->isEmpty()) ? round($products->sortBy([['price', 'desc']])->first()->price,2) : 0,
            "minProductPrice" => (!$products->isEmpty()) ? round($products->sortBy([['price', 'asc']])->first()->price,2) : 0,
        ];
    }
}
