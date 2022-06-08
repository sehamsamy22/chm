<?php

namespace App\Modules\Blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Http\Requests\BlogRequest;
use App\Modules\Blog\Repositories\WrappingTypeRepository;
use App\Modules\Blog\Transformers\BlogInfoResource;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $blogRepository;

    public function __construct(WrappingTypeRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
        $this->middleware('permission:blog-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:blog-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $blogs = $this->blogRepository->all();
        return $this->apiResponse((BlogInfoResource::collection($blogs)));
    }


    public function store(BlogRequest $request)
    {
        $blog = Blog::create($request->validated());
        return $this->apiResponse(new BlogInfoResource($blog));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $blog = Blog::find($id);
        return $this->apiResponse(new BlogInfoResource($blog));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(BlogRequest $request, $id)
    {
        $blog = Blog::find($id);
        $blog->update($request->validated());
        return $this->apiResponse(new BlogInfoResource($blog));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return $this->apiResponse(BlogInfoResource::collection(Blog::all()));
    }
}
