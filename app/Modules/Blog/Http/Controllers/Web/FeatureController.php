<?php

namespace App\Modules\Blog\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Entities\Feature;
use App\Modules\Blog\Transformers\FeatureResource;


class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::get()->take(3);
        return $this->apiResponse(FeatureResource::collection($features));
    }
}
