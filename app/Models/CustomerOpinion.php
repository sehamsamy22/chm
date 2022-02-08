<?php

namespace App\Models;

use App\Models\Admin;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class CustomerOpinion extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description'];
    protected $fillable = ['name', 'description', 'image'];


}
