<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\SubscriptionSizeRequest;
use App\Modules\Subscription\Http\Requests\WrappingRequest;
use App\Modules\Subscription\Repositories\SubscriptionSizeRepository;
use App\Modules\Subscription\Repositories\WrappingTypeRepository;
use App\Modules\Subscription\Transformers\SubscriptionSizeResource;
use App\Modules\Subscription\Transformers\WrappingTypeResource;
use Illuminate\Http\Response;

class SubscriptionSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     */
    private $subscriptionSizeRepository;

    public function __construct(SubscriptionSizeRepository $subscriptionSizeRepository)
    {
        $this->subscriptionSizeRepository = $subscriptionSizeRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $subscriptionSizes = $this->subscriptionSizeRepository->all();
        return $this->apiResponse((SubscriptionSizeResource::collection($subscriptionSizes)));
    }

    public function store(SubscriptionSizeRequest $request)
    {
        $subscriptionSize = SubscriptionSize::create($request->validated());
        return $this->apiResponse(new SubscriptionSizeResource($subscriptionSize));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $subscriptionSize = SubscriptionSize::find($id);
        return $this->apiResponse(new SubscriptionSizeResource($subscriptionSize));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(SubscriptionSizeRequest $request, $id)
    {
        $subscriptionSize = SubscriptionSize::find($id);
        $subscriptionSize->update($request->validated());
        return $this->apiResponse(new SubscriptionSizeResource($subscriptionSize));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $wrappingType = SubscriptionSize::findOrFail($id);
        $wrappingType->delete();
        return $this->apiResponse(SubscriptionSizeResource::collection(SubscriptionSize::all()));
    }
}
