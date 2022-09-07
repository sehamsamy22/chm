<?php

namespace App\Modules\Product\Transformers;

use App\Modules\Category\Transformers\CategoryResource;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductColor;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */


    public function toArray($request)
    {
        $user = Auth::guard('api')->user();
//        dd($this->options);
        return [
            'id' => $this->id,
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name'),
            'description' => $request->header('Content-language') ? $this->description : $this->getTranslations('description'),
            'image' => $this->image,
            'SKU' => $this->SKU,
            'stock' => (int)$this->stock,
            'price' =>$this->price,
//            "current_currency" => $request->header('currency') ??optional(optional(optional($this->store)->country)->currency)->code,
            'discount_price' => $this->discount_price,
            'max_per_order' => (int)$this->max_per_order,
            'discount_start_date' => $this->discount_start_date,
            'discount_end_date' => $this->discount_end_date,
            'deactivated_at' => $this->deactivated_at,
            'deactivation_notes' => $this->deactivation_notes,
            'digit' => $this->digit,
            'type' => $this->type,
            'is_package' => $this->is_package,
            'package_min' => $this->package_min,
            'package_max' => $this->package_max,
            'time_period' => $this->time_period,
            'bundle' => $this->bundle,
            'store_currency' => $this->store->country->currency->code  ?? '',
            'category' =>$request->header('Content-language') ? $this->category->name : $this->category->getTranslations('name'),
            'brand' => new BrandResource($this->brand),
            'bundle_products' => $this->bundle == 1 ? $this->bundles->transform(function ($product) {
                return new ProductResource(Product::find($product->product_id));
            }) : [],
            'wish' => in_array(optional($user)->id, $this->getWishesUsers('wish')),
            'compare' => in_array(optional($user)->id, $this->getWishesUsers('compare')),
            'rate_avg' => round((float)$this->rates->avg('rate_avg'), 2),
            'tags' => $this->tags->transform(function ($tag) {
                return $tag->tag ?? '';
            }),
            "options" =>ProductOptionResource::collection($this->options()->groupBy('product_option_value.option_id')->with(['values' => function ($q) {
                $q->where('product_option_value.product_id',$this->id)->get();
            }])->get()) ,
            'images' => $this->images->transform(function ($image) {
                return $image->image ?? '';
            }),
            'rates' => $this->rates->transform(function ($rate) {
                return [
                    'user' => $rate->name,
                    'user_image' => $rate->image,
                    'rate_avg' => $rate->pivot->rate_avg,
                    'product_negatives' => $rate->pivot->product_negatives,
                    'product_positives' => $rate->pivot->product_positives,
                    'created_at' => Carbon::parse($rate->created_at)->format('d-m-Y')
                ];
            }),
            'package_categories' => $this->packageCategories->transform(function ($category) {
                return   new CategoryResource($category->category);
            }),
        ];
    }

    public function getWishesUsers($type)
    {
        return $this->wishes->where('pivot.type', $type)->pluck('id')->toArray();
    }


}
