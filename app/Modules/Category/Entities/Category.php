<?php

namespace App\Modules\Category\Entities;

use App\Modules\Category\Database\factories\CategoryFactory;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\PackageCategory;

use App\Modules\Store\Entities\Store;
use App\Scopes\CategoryStoreScope;
use App\Scopes\NormalProductScope;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    public $translatable = ['name'];
    protected $softDelete = true;
    protected $fillable = ['name', 'image', 'parent_id', 'store_id', 'have_additions','is_package'];

    protected static function booted()
    {
        static::addGlobalScope(new CategoryStoreScope());
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            self::class,
            'parent_id', // Foreign key on the products table...
            'category_id', // Foreign key on the categories table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }

    public function options()
    {
        return $this->belongsToMany(Option::class);
    }

    public function categoryOptions()
    {
        return $this->hasMany(CategoryOption::class, 'category_id');
    }

    public function scopeHaveAdditions($query)
    {
        $query->where('have_additions', 1);
    }

    public function scopeNotHaveAdditions($query)
    {
        $query->where('have_additions', 0);
    }

    public function additions()
    {
        $additions = $this->hasMany(Product::class, 'category_id')->withoutGlobalScope(NormalProductScope::class)->get();
        return $additions;
    }

//    public function packageItems()
//    {
//        $items = $this->hasMany(PackageCategory::class, 'category_id')->withoutGlobalScope(NormalProductScope::class)->get();
//        return $items;
//    }

}
