<?php

namespace App\Modules\Cart\Entities;

use App\Modules\Subscription\Entities\SubscriptionItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;
use App\Modules\Cart\Entities\Cart;

class CartSubscriptionItem extends Model
{
    use HasFactory;
    protected $fillable = ['item_id','cart_id'];
//    protected $table = ['cart_subscription_items'];

    public function item()
    {
        return $this->belongsTo(SubscriptionItem::class, 'item_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
