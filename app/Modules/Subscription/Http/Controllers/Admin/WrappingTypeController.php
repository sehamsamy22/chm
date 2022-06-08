<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Http\Requests\WrappingRequest;
use App\Modules\Subscription\Repositories\WrappingTypeRepository;
use App\Modules\Subscription\Transformers\WrappingTypeResource;
use Illuminate\Http\Response;

class WrappingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    /**
     * @var WrappingTypeRepository
     */
    private $wrappingTypeRepository;

    public function __construct(WrappingTypeRepository $wrappingTypeRepository)
    {
        $this->wrappingTypeRepository = $wrappingTypeRepository;
//        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $wrappingTypes = $this->wrappingTypeRepository->all();
        return $this->apiResponse((WrappingTypeResource::collection($wrappingTypes)));
    }

    public function store(WrappingRequest $request)
    {
        $wrappingType = WrappingType::create($request->validated());
        return $this->apiResponse(new WrappingTypeResource($wrappingType));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $wrappingType = WrappingType::find($id);
        return $this->apiResponse(new WrappingTypeResource($wrappingType));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(WrappingRequest $request, $id)
    {
        $wrappingType = WrappingType::find($id);
        $wrappingType->update($request->validated());
        return $this->apiResponse(new WrappingTypeResource($wrappingType));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $wrappingType = WrappingType::findOrFail($id);
        $wrappingType->delete();
        return $this->apiResponse(WrappingTypeResource::collection(WrappingType::all()));
    }
}
