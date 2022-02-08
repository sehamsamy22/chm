<?php

namespace App\Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $fillable = ['blog_id','comment'];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

}
