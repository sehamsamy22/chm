<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OccasionRequest;
use App\Http\Resources\OccasionResource;
use App\Models\Occasion;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class OccasionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */

    public function index()
    {
        $occasions = Occasion::where('user_id', auth()->user()->id)->where('date','>',Carbon::now())->orderBy('date', 'asc')->get();
        return $this->apiResponse(OccasionResource::collection($occasions));
    }

    /**
     * Store a newly created resource in storage.
     * @param OccasionRequest $request
     * @return JsonResponse
     */
    public function store(OccasionRequest $request)
    {
        $request['user_id'] = auth()->user()->id;
        $occasion = Occasion::create($request->all());
        return $this->apiResponse(new OccasionResource($occasion));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $occasion = Occasion::find($id);
        return $this->apiResponse(new OccasionResource($occasion));
    }

    /**
     * Update the specified resource in storage.
     * @param OccasionRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(OccasionRequest $request, $id)
    {
        $occasion = Occasion::find($id);
        $request['user_id'] = auth()->user()->id;
        $occasion->update($request->all());
        return $this->apiResponse(new OccasionResource($occasion));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $occasion = Occasion::findOrFail($id);
        $occasion->delete();
        return $this->apiResponse(OccasionResource::collection(Occasion::where('user_id', auth()->user()->id)->get()));
    }
}
