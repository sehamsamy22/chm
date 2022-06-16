<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\CareInstruction;
use App\Modules\Subscription\Entities\SubscriptionDayCount;
use App\Modules\Subscription\Entities\SubscriptionDeliveryCount;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Entities\SubscriptionType;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\CareInstructionRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionDayCountRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionDeliveryCountRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionSizeRequest;
use App\Modules\Subscription\Http\Requests\SubscriptionTypeRequest;
use App\Modules\Subscription\Repositories\SubscriptionDayCountRepository;
use App\Modules\Subscription\Repositories\SubscriptionDeliveryCountRepository;
use App\Modules\Subscription\Transformers\CareInstructionResource;
use App\Modules\Subscription\Transformers\SubscriptionDayCountResource;
use App\Modules\Subscription\Transformers\SubscriptionDeliveryCountResource;
use App\Modules\Subscription\Transformers\SubscriptionTypeResource;
use Illuminate\Http\Response;

class CareInstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     */
    private $careInstructionRepository;

    public function __construct(CareInstruction $careInstructionRepository)
    {
        $this->careInstructionRepository = $careInstructionRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $careInstructions = $this->careInstructionRepository->all();
        return $this->apiResponse((CareInstructionResource::collection($careInstructions)));
    }

    public function store(CareInstructionRequest $request)
    {
        $careInstruction = CareInstruction::create($request->validated());
        return $this->apiResponse(new CareInstructionResource($careInstruction));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $careInstruction = CareInstruction::find($id);
        return $this->apiResponse(new CareInstructionResource($careInstruction));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CareInstructionRequest $request, $id)
    {
        $careInstruction = CareInstruction::find($id);
        $careInstruction->update($request->validated());
        return $this->apiResponse(new CareInstructionResource($careInstruction));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->delete();
        return $this->apiResponse(CareInstructionResource::collection(CareInstruction::all()));
    }
}
