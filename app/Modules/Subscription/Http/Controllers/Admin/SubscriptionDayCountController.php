<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\SubscriptionDayCount;
use App\Modules\Subscription\Entities\SubscriptionDeliveryCount;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\SubscriptionType;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\SubscriptionDayCountRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionDeliveryCountRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionSizeRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionTypeRequest;
use App\Modules\Subscription\Repositories\SubscriptionDayCountRepository;
use App\Modules\Subscription\Repositories\SubscriptionDeliveryCountRepository;
use App\Modules\Subscription\Transformers\SubscriptionDayCountResource;
use App\Modules\Subscription\Transformers\SubscriptionDeliveryCountResource;
use App\Modules\Subscription\Transformers\SubscriptionTypeResource;
use Illuminate\Http\Response;

class SubscriptionDayCountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     */
    private $subscriptionDayCountRepository;

    public function __construct(SubscriptionDayCountRepository $subscriptionDayCountRepository)
    {
        $this->subscriptionDayCountRepository = $subscriptionDayCountRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $subscriptionDayCounts = $this->subscriptionDayCountRepository->all();
        return $this->apiResponse((SubscriptionDeliveryCountResource::collection($subscriptionDayCounts)));
    }

    public function store(SubscriptionDayCountRequest $request)
    {
        $subscriptionDayCount = SubscriptionDayCount::create($request->validated());
        return $this->apiResponse(new SubscriptionDayCountResource($subscriptionDayCount));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $subscriptionDayCount = SubscriptionDeliveryCount::find($id);
        return $this->apiResponse(new SubscriptionDayCountResource($subscriptionDayCount));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(SubscriptionDeliveryCountRequest $request, $id)
    {
        $subscriptionDayCount = SubscriptionDeliveryCount::find($id);
        $subscriptionDayCount->update($request->validated());
        return $this->apiResponse(new SubscriptionDayCountResource($subscriptionDayCount));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $subscriptionDayCount = SubscriptionDeliveryCount::findOrFail($id);
        $subscriptionDayCount->delete();
        return $this->apiResponse(SubscriptionDayCountResource::collection(SubscriptionDayCount::all()));
    }
}
