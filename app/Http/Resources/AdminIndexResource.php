<?php

namespace App\Http\Resources;

use App\Http\Resources\Customer\UserResource;
use App\Modules\Blog\Transformers\BlogInfoResource;
use App\Modules\Category\Transformers\OptionResource;
use App\Modules\Order\Transformers\OrderResource;
use App\Modules\Product\Transformers\ProductResource;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminIndexResource extends JsonResource
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
            'categories' => $this['categories'],
            'customers' => $this['customers'],
            'products' => $this['products'],
            'ads' => $this['ads'],
            'orders' => $this['orders'],
            'lastProducts' =>ProductResource::collection( $this['lastProducts']),
          'lastCustomer' => UserResource::collection($this['lastCustomer']),
           'lastOrders' =>OrderResource::collection($this['lastOrders']),
//            'moreOrderedProduct' => ProductResource::collection($this['moreOrderedProduct']),
//            'moreCommentedProduct' => ProductResource::collection($this['moreCommentedProduct']),


        ];
    }
}
