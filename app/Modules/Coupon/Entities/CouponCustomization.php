<?php

namespace App\Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCustomization extends Model
{
    use HasFactory;

    protected $fillable = ['model_id', 'model_type', 'coupon_id'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function model()
    {
        return $this->morphTo();
    }


}
