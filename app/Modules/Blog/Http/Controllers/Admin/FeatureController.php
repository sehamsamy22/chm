<?php

namespace App\Modules\Blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Entities\Feature;
use App\Modules\Blog\Http\Requests\FeatureRequest;
use App\Modules\Blog\Transformers\FeatureResource;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */


    public function index()
    {
        $features = Feature::all();
        return $this->apiResponse((FeatureResource::collection($features)));
    }


    public function store(FeatureRequest $request)
    {
        $feature = Feature::create($request->validated());
        return $this->apiResponse(new FeatureResource($feature));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $feature = Feature::find($id);
        return $this->apiResponse(new FeatureResource($feature));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(FeatureRequest $request, $id)
    {
        $feature = Feature::find($id);
        $feature->update($request->validated());
        return $this->apiResponse(new FeatureResource($feature));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();
        return $this->apiResponse(FeatureResource::collection(Feature::all()));
    }
}
