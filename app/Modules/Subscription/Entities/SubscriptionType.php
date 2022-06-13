<?php

namespace App\Modules\Subscription\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionType extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name',];

//    public function products()
//    {
//        return $this->hasMany(Product::class, 'brand_id');
//    }
}
