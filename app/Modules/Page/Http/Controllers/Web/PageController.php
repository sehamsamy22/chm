<?php

namespace App\Modules\Page\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Page\Entities\Page;
use App\Modules\Page\Http\Requests\PageRequest;
use Illuminate\Http\Response;
use App\Modules\Page\Transformers\PageInfoResource;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return $this->apiResponse( PageInfoResource::collection($pages));
    }
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return $this->apiResponse(new PageInfoResource($page));
    }
}
