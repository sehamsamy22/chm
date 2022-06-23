<?php

namespace App\Modules\Order\Entities;

use App\Modules\Subscription\Entities\SubscriptionItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;
use App\Modules\Cart\Entities\Cart;

class OrderSubscriptionItem extends Model
{
    use HasFactory;
    protected $fillable = ['item_id','order_id'];
//    protected $table = ['cart_subscription_items'];

    public function item()
    {
        return $this->belongsTo(SubscriptionItem::class, 'item_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
