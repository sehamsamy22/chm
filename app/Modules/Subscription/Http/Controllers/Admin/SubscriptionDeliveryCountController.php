<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\SubscriptionDeliveryCount;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\SubscriptionType;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\SubscriptionDeliveryCountRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionSizeRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionTypeRequest;
use App\Modules\Subscription\Repositories\SubscriptionDeliveryCountRepository;
use App\Modules\Subscription\Transformers\SubscriptionDeliveryCountResource;
use App\Modules\Subscription\Transformers\SubscriptionTypeResource;
use Illuminate\Http\Response;

class SubscriptionDeliveryCountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     */
    private $subscriptionDeliveryCountRepository;

    public function __construct(SubscriptionDeliveryCountRepository $subscriptionTDeliveryCountRepository)
    {
        $this->subscriptionDeliveryCountRepository = $subscriptionTDeliveryCountRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $subscriptionDeliveryCounts = $this->subscriptionDeliveryCountRepository->all();
        return $this->apiResponse((SubscriptionDeliveryCountResource::collection($subscriptionDeliveryCounts)));
    }

    public function store(SubscriptionDeliveryCountRequest $request)
    {
        $subscriptionDeliveryCount = SubscriptionDeliveryCount::create($request->validated());
        return $this->apiResponse(new SubscriptionDeliveryCountResource($subscriptionDeliveryCount));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $subscriptionDeliveryCount = SubscriptionDeliveryCount::find($id);
        return $this->apiResponse(new SubscriptionDeliveryCountResource($subscriptionDeliveryCount));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(SubscriptionDeliveryCountRequest $request, $id)
    {
        $subscriptionDeliveryCount = SubscriptionDeliveryCount::find($id);
        $subscriptionDeliveryCount->update($request->validated());
        return $this->apiResponse(new SubscriptionDeliveryCountResource($subscriptionDeliveryCount));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $subscriptionDeliveryCount = SubscriptionDeliveryCount::findOrFail($id);
        $subscriptionDeliveryCount->delete();
        return $this->apiResponse(SubscriptionDeliveryCountResource::collection(SubscriptionDeliveryCount::all()));
    }
}
