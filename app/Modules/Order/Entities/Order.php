<?php

namespace App\Modules\Order\Entities;

use App\Models\User;
use App\Modules\Address\Entities\Address;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Product\Entities\Product;
use App\Modules\Store\Entities\Store;
use App\Scopes\OrderStoreScope;
use App\Scopes\StoreScope;
use App\Traits\helperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    const PENDING = "pending";
    const PAID = "paid";
    const DELIVERED = "delivered";
    const FINISHED = "finished";
    const CANCELLED = "cancelled";
    const RETURNED = "returned";

    protected $attributes = [
        'status' => self::PENDING
    ];

    use SoftDeletes, helperTrait;

    protected $fillable = ['user_id', 'address_id', 'method_id', 'status', 'total', 'unique_id',
    'amount', 'store_id','transaction_id','notes','pickup_date','time_id','received_name','gift_url'];

    protected static function booted()
    {
        static::addGlobalScope(new OrderStoreScope());
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function pickupTime()
    {
        return $this->belongsTo(PickupTime::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'price')->withTimestamps();
    }

    public function history()
    {
        return $this->hasMany(OrderHistory::class, 'order_id');
    }

    public function coupon()
    {
        return $this->hasOne(CouponOrder::class);
    }

    public function invoice()
    {
        return $this->hasOne(OrderInvoice::class);
    }
}
