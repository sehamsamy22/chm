<?php

namespace App\Modules\Blog\Entities;

use App\Models\Admin;
use App\Modules\Category\Entities\Category;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasTranslations, SoftDeletes;

    public $translatable = ['name'];
    protected $fillable = ['name'];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }

}
