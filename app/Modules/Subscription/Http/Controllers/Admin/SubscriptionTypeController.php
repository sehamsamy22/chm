<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\SubscriptionType;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\SubscriptionSizeRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionTypeRequest;
use App\Modules\Subscription\Http\Requests\WrappingRequest;
use App\Modules\Subscription\Repositories\SubscriptionSizeRepository;
use App\Modules\Subscription\Repositories\SubscriptionTypeRepository;
use App\Modules\Subscription\Repositories\WrappingTypeRepository;
use App\Modules\Subscription\Transformers\SubscriptionSizeResource;
use App\Modules\Subscription\Transformers\SubscriptionTypeResource;
use App\Modules\Subscription\Transformers\WrappingTypeResource;
use Illuminate\Http\Response;

class SubscriptionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     */
    private $subscriptionTypeRepository;

    public function __construct(SubscriptionTypeRepository $subscriptionTypeRepository)
    {
        $this->subscriptionTypeRepository = $subscriptionTypeRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $subscriptionTypes = $this->subscriptionTypeRepository->all();
        return $this->apiResponse((SubscriptionTypeResource::collection($subscriptionTypes)));
    }

    public function store(SubscriptionTypeRequest $request)
    {
        $subscriptionType = SubscriptionType::create($request->validated());
        return $this->apiResponse(new SubscriptionTypeResource($subscriptionType));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $subscriptionType = SubscriptionType::find($id);
        return $this->apiResponse(new SubscriptionTypeResource($subscriptionType));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(SubscriptionTypeRequest $request, $id)
    {
        $subscriptionType = SubscriptionType::find($id);
        $subscriptionType->update($request->validated());
        return $this->apiResponse(new SubscriptionTypeResource($subscriptionType));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $wrappingType = SubscriptionType::findOrFail($id);
        $wrappingType->delete();
        return $this->apiResponse(SubscriptionTypeResource::collection(SubscriptionType::all()));
    }
}
