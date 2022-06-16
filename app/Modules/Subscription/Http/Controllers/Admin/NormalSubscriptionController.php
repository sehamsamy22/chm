<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\CareInstruction;
use App\Modules\Subscription\Entities\NormalSubscription;
use App\Modules\Subscription\Entities\SubscriptionDayCount;
use App\Modules\Subscription\Entities\SubscriptionDeliveryCount;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\SubscriptionType;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\CareInstructionRequest;
use App\Modules\Subscription\Http\Requests\NormalSubscriptionRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionDayCountRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionDeliveryCountRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionSizeRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionTypeRequest;
use App\Modules\Subscription\Repositories\SubscriptionDayCountRepository;
use App\Modules\Subscription\Repositories\SubscriptionDeliveryCountRepository;
use App\Modules\Subscription\Transformers\CareInstructionResource;
use App\Modules\Subscription\Transformers\NormalSubscriptionResource;
use App\Modules\Subscription\Transformers\SubscriptionDayCountResource;
use App\Modules\Subscription\Transformers\SubscriptionDeliveryCountResource;
use App\Modules\Subscription\Transformers\SubscriptionTypeResource;
use Illuminate\Http\Response;

class NormalSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     */
    private $normalSupscription;

    public function __construct(NormalSubscription $normalSupscriptionRepository)
    {
        $this->normalSupscriptionRepository = $normalSupscriptionRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $normalSubscriptions = $this->normalSupscriptionRepository->all();
        return $this->apiResponse((NormalSubscriptionResource::collection($normalSubscriptions)));
    }

    public function store(NormalSubscriptionRequest $request)
    {
        $normalSubscription = NormalSubscription::create($request->validated());
        return $this->apiResponse(new NormalSubscriptionResource($normalSubscription));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $normalSubscription = NormalSubscription::find($id);
        return $this->apiResponse(new NormalSubscriptionResource($normalSubscription));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(NormalSubscriptionRequest $request, $id)
    {
        $normalSubscription = NormalSubscription::find($id);
        $normalSubscription->update($request->validated());
        return $this->apiResponse(new NormalSubscriptionResource($normalSubscription));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $normalSubscription = NormalSubscription::findOrFail($id);
        $normalSubscription->delete();
        return $this->apiResponse(NormalSubscriptionResource::collection(NormalSubscription::all()));
    }
}
