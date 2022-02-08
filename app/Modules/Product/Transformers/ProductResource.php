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
        //        dd(exchangeRate(100,'SAR'));
        $user = Auth::guard('api')->user();
        return [
            'id' => $this->id,
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name'),
            'description' => $request->header('Content-language') ? $this->description : $this->getTranslations('description'),
            'image' => $this->image,
            'SKU' => $this->SKU,
            'stock' => (int)$this->stock,
            'price' => round(exchangeRate($this->price, $request->header('currency')), 2),
            "current_currency" => $request->header('currency') ??optional(optional(optional($this->store)->country)->currency)->code,
            'discount_price' => round(exchangeRate($this->discount_price, $request->header('currency')), 2),
            'max_per_order' => (int)$this->max_per_order,
            'creator' => $this->creator->name,
            'discount_start_date' => $this->discount_start_date,
            'discount_end_date' => $this->discount_end_date,
            'deactivated_at' => $this->deactivated_at,
            'deactivation_notes' => $this->deactivation_notes,
            'digit' => $this->digit,
            'type' => $this->type,
            'time_period' => $this->time_period,
            'bundle' => $this->bundle,
            'store_currency' => $this->store->country->currency->code ?? getsetting('defaultCurrency') ?? '',
            'category' => new CategoryResource($this->category),
            'brand' => new BrandResource($this->brand),
            'bundle_products' => $this->bundle == 1 ? $this->bundles->transform(function ($product) {
                return new ProductResource(Product::find($product->product_id));
            }) : [],
            'wish' => in_array(optional($user)->id, $this->getWishesUsers('wish')),
            'compare' => in_array(optional($user)->id, $this->getWishesUsers('compare')),
            'rate_avg' => (double)round($this->rates()->avg('rate_avg'), 2),
            'tags' => $this->tags->transform(function ($tag) {
                return $tag->tag ?? '';
            }),
            'options' => count($this->options) ? $this->options->transform(function ($option) use ($request) {
                $color = ProductColor::find($option->pivot->color_id);
                return [
                    'option_id' => $option->id,
                    'option_type' => $option->input_type ?? '',
                    'option' => $request->header('Content-language') ? $option->name : $option->getTranslations('name'),
                    "color_id" => $option->pivot->color_id,
                    'color_name' => ($color) ? $request->header('Content-language') ? $color->name : "" : '',
                    'value' => $option->input_type == 'check' ? $option->pivot->value : ProductColor::find($option->pivot->color_id)->color ?? '',
                ];
            }) : [],
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
        ];
    }

    public function getWishesUsers($type)
    {
        return $this->wishes->where('pivot.type', $type)->pluck('id')->toArray();
    }


}
