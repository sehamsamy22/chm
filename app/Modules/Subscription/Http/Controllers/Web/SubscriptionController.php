<?php

namespace App\Modules\Subscription\Http\Controllers\Web;

use App\Modules\Subscription\Entities\CareInstruction;
use App\Modules\Subscription\Entities\NormalSubscription;
use App\Modules\Subscription\Entities\Subscription;
use App\Modules\Subscription\Entities\SubscriptionDayCount;
use App\Modules\Subscription\Entities\SubscriptionDeliveryCount;
use App\Modules\Subscription\Entities\SubscriptionItem;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\SubscriptionType;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\SubscriptionDayCountRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionRequest;
use App\Modules\Subscription\Transformers\CareInstructionResource;
use App\Modules\Subscription\Transformers\NormalSubscriptionResource;
use App\Modules\Subscription\Transformers\SubscriptionDayCountResource;
use App\Modules\Subscription\Transformers\SubscriptionDeliveryCountResource;
use App\Modules\Subscription\Transformers\SubscriptionResource;
use App\Modules\Subscription\Transformers\SubscriptionTypeResource;
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

    public function types()
    {
        $types = SubscriptionType::all();
        return $this->apiResponse(SubscriptionTypeResource::collection($types));
    }
    public function days()
    {
        $days = SubscriptionDayCount::all();
        return $this->apiResponse(SubscriptionDayCountResource::collection($days));
    }

    public function deliveries()
    {
        $deliveries = SubscriptionDeliveryCount::all();
        return $this->apiResponse(SubscriptionDeliveryCountResource::collection($deliveries));
    }

    public function careInstructions()
    {
        $careInstructions = CareInstruction::all();
        return $this->apiResponse(CareInstructionResource::collection($careInstructions));
    }
    public function normalSubscriptions()
    {
        $normalSubscriptions = NormalSubscription::all();
        return $this->apiResponse(NormalSubscriptionResource::collection($normalSubscriptions));
    }

    public function subscriptions(SubscriptionRequest $request)
    {
        $subscription = Subscription::create($request->validated());
        return $this->apiResponse(new SubscriptionResource($subscription));
    }

}
