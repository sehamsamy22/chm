<?php
namespace App\Http\Controllers\Admins;
use App\Http\Controllers\Controller;
use App\Http\Resources\OccasionResource;
use App\Models\Occasion;
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
        $occasions = Occasion::all();
        return $this->apiResponse(OccasionResource::collection($occasions));
    }

    /**
     * Store a newly created resource in storage.
     * @param AreaRequest $request
     * @return JsonResponse
     */
    public function show($id)
    {
        $occasion = Occasion::find($id);
        return $this->apiResponse(new OccasionResource($occasion));
    }


}
