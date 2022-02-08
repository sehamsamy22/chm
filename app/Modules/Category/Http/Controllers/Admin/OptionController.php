<?php

namespace App\Modules\Category\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Category\Entities\Option;
use App\Modules\Category\Http\Requests\OptionRequest;
use App\Modules\Category\Repositories\OptionRepository;
use App\Modules\Category\Transformers\OptionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
        $this->middleware('permission:option-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:option-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:option-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:option-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $options = $this->optionRepository->all();
        return $this->apiResponse(OptionResource::collection($options));
    }

    /**
     * Store a newly created resource in storage.
     * @param OptionRequest $request
     * @return JsonResponse
     */
    public function store(OptionRequest $request)
    {
        $option = Option::create($request->validated());
        return $this->apiResponse(new OptionResource($option));
    }

    /**
     * Show the specified resource.
     * @param Option $option
     * @param int $id
     * @return JsonResponse
     */
    public function show(Option $option)
    {
        return $this->apiResponse(new OptionResource($option));
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    /**
     * Update the specified resource in storage.
     * @param Option $option
     * @param OptionRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(OptionRequest $request, Option $option)
    {
        $option->update($request->validated());
        return $this->apiResponse(new OptionResource($option));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @param Option $option
     * @return JsonResponse
     */
    public function destroy(Option $option)
    {
        $option->delete();
        return $this->apiResponse(OptionResource::collection(Option::all()));
    }
}
