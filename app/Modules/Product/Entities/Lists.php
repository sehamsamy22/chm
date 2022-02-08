<?php

namespace App\Modules\Product\Entities;

use App\Scopes\ListStoreScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class Lists extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    public $translatable = ['name', 'description'];
    protected $fillable = ['name', 'description', 'image','type','store_id'];

    protected static function booted()
    {
        static::addGlobalScope(new ListStoreScope());
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'list_product', 'list_id', 'product_id')->withTimestamps();
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class,'list_id');
    }

}
