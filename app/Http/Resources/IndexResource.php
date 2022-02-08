<?php

namespace App\Http\Resources;

use App\Modules\Ad\Transformers\AdResource;
use App\Modules\Address\Transformers\CountryResource;
use App\Modules\Category\Transformers\CategoryResource;
use App\Modules\Product\Transformers\ProductResource;
use App\Modules\Setting\Transformers\SettingResource;
use App\Modules\Store\Transformers\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Blog\Transformers\BlogInfoResource;

class IndexResource extends JsonResource
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
            'app_settings' => $this['app_settings'],
            'social_settings' => SettingResource::collection($this['social_settings']),
            'footerLinks' => $this['footerLinks'],
            'blogs' => BlogInfoResource::collection($this['blogs']),
            'countries' => CountryResource::collection($this['countries']),
            'banners' => AdResource::collection(collect($this['ads'])->where('location_id', 1)),
            'mid_left' => AdResource::collection(collect($this['ads'])->where('location_id', 3)),
            'mid_right' => AdResource::collection(collect($this['ads'])->where('location_id', 4)),
            'footer' => AdResource::collection(collect($this['ads'])->where('location_id', 2)),
            'categories' => CategoryResource::collection($this['categories']),
            'moreOrderedProduct' => ProductResource::collection($this['moreOrderedProduct']),
            'moreCommentedProduct' => ProductResource::collection($this['moreCommentedProduct']),

        ];
    }
}
