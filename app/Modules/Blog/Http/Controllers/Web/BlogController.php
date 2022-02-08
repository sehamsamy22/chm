<?php

namespace App\Modules\Blog\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Entities\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Transformers\CategoryResource;
use App\Modules\Blog\Transformers\BlogInfoResource;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return $this->apiResponse(BlogInfoResource::collection($blogs));
    }

    public function show($id)
    {
        $blog = Blog::find($id);

//        if(request('views'))
//        $blog->increment('views'); // add a new page view to our `views` column by incrementing it

        return $this->apiResponse(new BlogInfoResource($blog));
    }

    public function categories()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return $this->apiResponse(CategoryResource::collection($categories));
    }

    public function blogSimilar($id)
    {
        $blog = Blog::find($id);
        $blogs = Blog::where('category_id',$blog->category_id)->where('id','!=',$id)->get();
        return $this->apiResponse(BlogInfoResource::collection($blogs));
    }
    public function categoryBlogs($id)
    {
        $blogs = Blog::where('category_id',$id)->get();
        return $this->apiResponse(BlogInfoResource::collection($blogs));
    }
    public function blogCategories()
    {
        $blogs = BlogCategory::with('blogs')->get();
        return $this->apiResponse(CategoryResource::collection($blogs));
    }

    public function blog_search(Request $request)
    {
        $blogs = Blog::query();
        $blogs->where(function ($q) use ($request) {
            $q->Where('title', 'like', "%$request->key%")
                ->orWhere('description', 'like', "%$request->key%");
        });
        $blogs->orWhereHas('category', function ($q) use ($request) {
            $q->where('name', 'like', "%$request->key%");
        });
        return $this->apiResponse(BlogInfoResource::collection($blogs->get()));
    }
}
