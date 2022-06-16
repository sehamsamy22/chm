<?php

namespace App\Modules\Subscription\Entities;

use App\Modules\Order\Entities\PickupTime;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'size_id', 'type_id', 'delivery_id', 'wrapping_type_id', 'day_count_id', 'time_id','type','normal_subscription_id','store_id'];

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class, 'type_id');
    }

    public function size()
    {
        return $this->belongsTo(SubscriptionSize::class, 'size_id');
    }

    public function delivery()
    {
        return $this->belongsTo(SubscriptionDeliveryCount::class, 'delivery_id');
    }

    public function wrappingType()
    {
        return $this->belongsTo(WrappingType::class, 'wrapping_type_id');
    }
    public function dayCount()
    {
        return $this->belongsTo(SubscriptionDayCount::class, 'day_count_id');
    }
    public function time()
    {
        return $this->belongsTo(PickupTime::class, 'time_id');
    }
    public function normalSubscription()
    {
        return $this->belongsTo(NormalSubscription::class, 'normal_subscription_id');
    }
}
