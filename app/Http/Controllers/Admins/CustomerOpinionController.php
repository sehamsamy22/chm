<?php
namespace App\Http\Controllers\Admins;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerOpinionResource;
use App\Models\CustomerOpinion;
use App\Modules\Address\Http\Requests\AreaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CustomerOpinionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */

    public function index()
    {
        $customerOpinions = CustomerOpinion::all();
        return $this->apiResponse(CustomerOpinionResource::collection($customerOpinions));
    }

    /**
     * Store a newly created resource in storage.
     * @param AreaRequest $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $customerOpinion = CustomerOpinion::create($request->all());
        return $this->apiResponse(new CustomerOpinionResource($customerOpinion));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $customerOpinion = CustomerOpinion::find($id);
        return $this->apiResponse(new CustomerOpinionResource($customerOpinion));
    }

    /**
     * Update the specified resource in storage.
     * @param AreaRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $customerOpinion = CustomerOpinion::find($id);
        $customerOpinion->update($request->all());
        return $this->apiResponse(new CustomerOpinionResource($customerOpinion));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $customerOpinion = CustomerOpinion::findOrFail($id);
        $customerOpinion->delete();
        return $this->apiResponse(CustomerOpinionResource::collection(CustomerOpinion::all()));
    }
}
