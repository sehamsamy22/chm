<?php

namespace App\Modules\Page\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Page\Repositories\PageRepository;
use App\Modules\Page\Entities\Page;
use App\Modules\Page\Http\Requests\PageRequest;
use Illuminate\Http\Response;
use App\Modules\Page\Transformers\PageInfoResource;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->middleware('permission:page-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:page-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:page-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:page-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $pages = $this->pageRepository->all();
        return $this->apiResponse(PageInfoResource::collection($pages));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(PageRequest $request)
    {
        $page = Page::create($request->validated());
        return $this->apiResponse(new PageInfoResource($page));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $page = Page::find($id);
        return $this->apiResponse(new PageInfoResource($page));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(PageRequest $request, $id)
    {
        $page = Page::find($id);
        $page->update($request->validated());
        return $this->apiResponse(new PageInfoResource($page));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        return $this->apiResponse(PageInfoResource::collection(Page::all()));
    }
}
