<?php

namespace App\Modules\Subscription\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NormalSubscription extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['description','title'];
    protected $fillable = ['description','title','price','image'];


}
