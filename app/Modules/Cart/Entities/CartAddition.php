<?php

namespace App\Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class BundleProduct extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','addition_id','cart_id'];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function addition()
    {
        
        return $this->belongsTo(Product::class, 'addition_id');
    }
}
