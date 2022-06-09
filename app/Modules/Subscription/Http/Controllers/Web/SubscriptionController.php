<?php

namespace App\Modules\Subscription\Http\Controllers\Web;

use App\Modules\Subscription\Entities\SubscriptionItem;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\WrappingType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Modules\Subscription\Http\Requests\SubscriptionItemRequest;
use App\Modules\Subscription\Transformers\SubscriptionItemResource;
use App\Modules\Subscription\Transformers\SubscriptionSizeResource;
use App\Modules\Subscription\Transformers\WrappingTypeResource;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{

    public function wrappingTypes()
    {
        $wrappingTypes = WrappingType::all();
        return $this->apiResponse(WrappingTypeResource::collection($wrappingTypes));
    }

    public function sizes()
    {
        $sizes = SubscriptionSize::all();
        return $this->apiResponse(SubscriptionSizeResource::collection($sizes));
    }

    public function items()
    {
        $items = SubscriptionItem::all();
        return $this->apiResponse(SubscriptionItemResource::collection($items));
    }




}
