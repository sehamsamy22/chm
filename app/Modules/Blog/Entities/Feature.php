<?php

namespace App\Modules\Blog\Entities;

use App\Models\Admin;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description'];
    protected $fillable = ['title', 'description', 'image'];


}
