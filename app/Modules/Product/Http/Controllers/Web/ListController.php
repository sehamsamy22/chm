<?php

namespace App\Modules\Product\Http\Controllers\Web;

use App\Modules\Product\Entities\Lists;
use App\Modules\Product\Http\Requests\ListRequest;
use App\Modules\Product\Transformers\ListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class ListController extends Controller
{
    public function occasions()
    {
        $lists = Lists::where('type','occasions')->get();
        return $this->apiResponse(ListResource::collection($lists));
    }
    public function designers()
    {
        $lists = Lists::where('type','designers')->get();
        return $this->apiResponse(ListResource::collection($lists));
    }
    public function show($id)
    {
        $list = Lists::findOrFail($id);
        return $this->apiResponse(new ListResource($list));
    }

}
