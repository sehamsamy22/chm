<?php

namespace App\Modules\Coupon\Entities;

use App\Modules\Order\Entities\CouponOrder;
use App\Modules\Order\Entities\Order;
use App\Modules\Product\Entities\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class Coupon extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name', 'prom_code', 'type', 'start_date', 'end_date', 'prom_time', 'used_count', 'deactivated_at', 'max_limit', 'amount'];

    public function scopeActive($query)
    {
        return $query->where('deactivated_at', '=', NULL);
    }

    public function couponOrder()
    {
        return $this->hasMany(CouponOrder::class, 'coupon_id');
    }

    public function customizations()
    {
        return $this->hasMany(CouponCustomization::class, 'coupon_id');
    }
    public function modelable()
    {
        return $this->morphMany(CouponCustomization::class, 'model');
    }

}
