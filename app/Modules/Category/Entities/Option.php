<?php

namespace App\Modules\Category\Entities;

use App\Modules\Product\Entities\ProductOptionValue;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;
    public $translatable = ['name'];

    const INPUT_TYPE = ['color', 'check'];
    protected $fillable = ['name', 'input_type'];
    public function values()
    {
        return $this->hasMany(ProductOptionValue::class, 'option_id')->with('color');
    }


}
