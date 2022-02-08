<?php

namespace App\Modules\Product\Entities;

use App\Modules\Category\Entities\Option;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class ProductOptionValue extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'option_id', 'value', 'color_id'];
    protected $table = 'product_option_value';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
    }

    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'color_id');
    }
}
