<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\Subscription;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\SubscriptionSizeRequest;
use App\Modules\Subscription\Http\Requests\WrappingRequest;
use App\Modules\Subscription\Repositories\SubscriptionRepository;
use App\Modules\Subscription\Repositories\SubscriptionSizeRepository;
use App\Modules\Subscription\Repositories\WrappingTypeRepository;
use App\Modules\Subscription\Transformers\SubscriptionSizeResource;
use App\Modules\Subscription\Transformers\WrappingTypeResource;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     */
    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $subscriptions = $this->subscriptionRepository->all();
        return $this->apiResponse((SubscriptionSizeResource::collection($subscriptions)));
    }



    public function show($id)
    {
        $subscription = Subscription::find($id);
        return $this->apiResponse(new SubscriptionSizeResource($subscription));
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $subscription = Subscription::find($id);
        $subscription->delete();
        return $this->apiResponse(SubscriptionSizeResource::collection(Subscription::all()));
    }
}
