<?php

namespace App\Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','tag'];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

}
