<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\SubscriptionItem;
use App\Modules\Subscription\Http\Requests\SubscriptionItemRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionSizeRequest;
use App\Modules\Subscription\Repositories\SubscriptionItemRepository;
use App\Modules\Subscription\Transformers\SubscriptionItemResource;
use Illuminate\Http\Response;

class SubscriptionItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     */
    private $subscriptionItemRepository;

    public function __construct(SubscriptionItemRepository $subscriptionItemRepository)
    {
        $this->subscriptionItemRepository = $subscriptionItemRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $subscriptionItems = $this->subscriptionItemRepository->all();
        return $this->apiResponse((SubscriptionItemResource::collection($subscriptionItems)));
    }

    public function store(SubscriptionItemRequest $request)
    {
        $subscriptionItem = SubscriptionItem::create($request->validated());
        return $this->apiResponse(new SubscriptionItemResource($subscriptionItem));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $subscriptionItem = SubscriptionItem::find($id);
        return $this->apiResponse(new SubscriptionItemResource($subscriptionItem));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(SubscriptionSizeRequest $request, $id)
    {
        $subscriptionItem = SubscriptionItem::find($id);
        $subscriptionItem->update($request->validated());
        return $this->apiResponse(new SubscriptionItemResource($subscriptionItem));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $subscriptionItem = SubscriptionItem::find($id);
        $subscriptionItem->delete();
        return $this->apiResponse(SubscriptionItemResource::collection(SubscriptionItem::all()));
    }
}
