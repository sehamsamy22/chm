<?php

namespace App\Modules\Blog\Entities;

use App\Models\Admin;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description'];
    protected $fillable = ['title', 'description', 'image', 'views', 'admin_id', 'category_id'];

    public function Author()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }
}
