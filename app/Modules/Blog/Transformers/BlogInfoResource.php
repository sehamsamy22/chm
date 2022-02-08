<?php

namespace App\Modules\Blog\Transformers;

use App\Modules\Blog\Entities\Blog;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $created_at = $this->created_at;
        if ($request->header('Content-language'))
            $created_at = $request->header('Content-language') == 'ar' ? $this->created_at->locale('ar')->diffForHumans() :
                $this->created_at->locale('en')->diffForHumans();
        return [
            'id' => $this->id,
            'title' => $request->header('Content-language') ? $this->title : $this->getTranslations('title'),
            'description' => $request->header('Content-language') ? $this->description : $this->getTranslations('description'),
            'image' => $this->image,
            'admin' => $this->Author->name,
//           'category_id'=>$this->category_id,

//            'categories'=>CategoryResource::collection(Category::orderBy('created_at', 'desc')->get()->take(3)),
            'views' => $this->views,
            'comments_count' => count($this->comments),
            'created_at' => $created_at,
            'category' => $request->header('Content-language') ? $this->category->name : $this->category->getTranslations('name'),
            'latest_Blogs' => Blog::orderBy('created_at', 'desc')->get()->take(3)->transform(function ($q) use ($request, $created_at) {
                return [
                    'id' => $q->id,
                    'title' => $request->header('Content-language') ? $q->title : $q->getTranslations('title'),
                    'description' =>$request->header('Content-language') ? $q->description : $q->getTranslations('description'),
                    'image' => url($q->image),
//                    'category'=>$q->category->name??'',
                    'created_at' => $created_at,


                ];
            }),

        ];
    }
}
