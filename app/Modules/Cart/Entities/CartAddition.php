<?php

namespace App\Modules\Cart\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;
use App\Modules\Cart\Entities\Cart;

class CartAddition extends Model
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
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
