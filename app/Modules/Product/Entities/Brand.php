<?php

namespace App\Modules\Product\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name','image','logo'];

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
