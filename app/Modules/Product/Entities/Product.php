<?php

namespace App\Modules\Product\Entities;

use App\Models\Admin;
use App\Models\User;
use App\Modules\Ad\Entities\Ad;
use App\Modules\Category\Entities\Category;
use App\Modules\Category\Entities\Option;
use App\Modules\Order\Entities\Order;
use App\Modules\Product\Database\factories\ProductFactory;
use App\Modules\Store\Entities\Store;
use App\Modules\Cart\Entities\CartAddition;
use App\Scopes\HasCategoryScope;
use App\Scopes\NormalProductScope;
use App\Scopes\ProductStoreScope;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'description', 'SKU', 'price', 'discount_price', 'discount_start_date', 'discount_end_date', 'image', 'stock', 'deactivated_at', 'deactivation_notes', 'max_per_order'
        , 'digit', 'category_id', 'creator_id', 'bundle', 'store_id', 'type', 'time_period', 'brand_id','is_package' ,'package_min',
        'package_max',
    ];

    public $translatable = ['name', 'description'];

//    /**
//     * The "booting" method of the model.
//     *
//     * @return void
//     */
    protected static function booted()
    {
    //    static::addGlobalScope(new ProductStoreScope());
    //    static::addGlobalScope(new HasCategoryScope ());
    //    static::addGlobalScope(new NormalProductScope());
    }

//
    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    public function comments()
    {
        return $this->belongsToMany(User::class, 'product_comment', 'product_id', 'user_id')->withPivot('comment')->withTimestamps();
    }

    public function rates()
    {
        return $this->belongsToMany(User::class, 'product_rate', 'product_id', 'user_id')->withPivot('rate_avg', 'product_negatives', 'product_positives')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'creator_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'product_option_value', 'product_id', 'option_id')
            ->withPivot('value', 'color_id')->withTimestamps();
    }

    public function values()
    {
        return $this->hasMany(ProductOptionValue::class, 'product_id');
    }

    public function wishes()
    {
        return $this->belongsToMany(User::class, 'product_wish', 'product_id', 'user_id')->withPivot('type')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('deactivated_at', NULL);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function bundles()
    {
        return $this->hasMany(BundleProduct::class, 'parent_id');
    }

    public function packageCategories()
    {
        return $this->hasMany(PackageCategory::class, 'product_id');
    }

    public function Lists()
    {
        return $this->belongsToMany(Lists::class, 'list_product', 'product_id', 'list_id')->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_product','product_id', 'order_id');
    }

    public function ads()
    {
        return $this->morphMany(Ad::class, 'model');
    }
    public function additions()
    {
        return $this->hasMany(CartAddition::class,'addition_id');
    }
}
