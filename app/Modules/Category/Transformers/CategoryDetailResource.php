<?php

namespace App\Modules\Category\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDetailResource extends JsonResource
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
            'have_additions' => $this->have_additions,
            'parentCategory' => $this->mainCategory,
            "subcategories" => $this->subcategories->map(function ($subcategory) use ($request) {
                return [
                    "id" => $subcategory->id,
                    "name" => $request->header('content-language') ? $subcategory->name : $subcategory->getTranslations('name') ?? '',
                    "image" => $subcategory->image,
                    "options" => CategoryOptionResource::collection($subcategory->categoryOptions),
                ];
            }),
            "options" => CategoryOptionResource::collection($this->categoryOptions->unique("option_id")),
//            "products" => ProductResource::collection($this->products),
            "moreOrderedProduct" => $this->products()->with('orders')->get()->sortByDesc(function ($product) {
                return $product->orders->count();
            })->take(4),
            "maxProductPrice" => (!$products->isEmpty()) ? $products->sortBy([['price', 'desc']])->first()->price : 0,
            "minProductPrice" => (!$products->isEmpty()) ? $products->sortBy([['price', 'asc']])->first()->price : 0,
        ];
    }
}
