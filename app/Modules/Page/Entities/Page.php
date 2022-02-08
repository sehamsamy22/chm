<?php

namespace App\Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description'];
    protected $fillable = ['title', 'description', 'url', 'image'];
}
