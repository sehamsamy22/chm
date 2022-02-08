<?php

namespace App\Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Product\Entities\Promotion;
use App\Modules\Product\Http\Requests\Promotion as PromotionRequest;
use App\Modules\Product\Repositories\PromotionRepository;
use App\Modules\Product\Transformers\PromotionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PromotionController extends Controller
{

    private $promotionRepository;
    public function __construct(PromotionRepository $promotionRepository)
    {
        $this->promotionRepository = $promotionRepository;
        $this->middleware('permission:promotion-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:promotion-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:promotion-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:promotion-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = $this->promotionRepository->all();
        return $this->apiResponse(PromotionResource::collection($promotions));
    }

    public function store(PromotionRequest $request)
    {
        $promotion = $this->promotionRepository->store($request->all());
        return $this->apiResponse(new PromotionResource($promotion));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Promotion $promotion
     * @return JsonResponse
     */
    public function update(Request $request, Promotion $promotion)
    {
        $promotion = $this->promotionRepository->update($request->all(), $promotion);
        return $this->apiResponse(new PromotionResource($promotion));
    }

    /**
     * Remove the specified resource from storage.
     * @param Promotion $promotion
     * @return JsonResponse
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return $this->apiResponse(null);
    }
}
