<?php

namespace App\Modules\Cart\Entities;

use App\Models\User;
use App\Modules\Product\Entities\Product;
use App\Modules\Store\Entities\Store;
use App\Scopes\CartStoreScope;
use App\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'ordered_at', 'order_id', 'store_id'];

    protected static function booted()
    {
     static::addGlobalScope(new CartStoreScope());
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'price')->withTimestamps()->withoutGlobalScopes();
    }
}
