<?php

namespace App\Modules\Product\Http\Controllers\Web;

use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Lists;
use App\Modules\Product\Http\Requests\ListRequest;
use App\Modules\Product\Transformers\BrandDetailResource;
use App\Modules\Product\Transformers\BrandResource;
use App\Modules\Product\Transformers\ListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class BrandController extends Controller
{
    public function brands()
    {
        $brands = Brand::all();
        return $this->apiResponse(BrandResource::collection($brands));
    }
    public function brandProducts($id)
    {
        $brands = Brand::findOrFail($id);
        return $this->apiResponse(new BrandDetailResource($brands));
    }
}
