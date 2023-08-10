<?php

namespace App\Models;

use App\Modules\Address\Entities\Address;
use App\Modules\Address\Entities\Area;
use App\Modules\Address\Entities\City;
use App\Modules\Address\Entities\Country;
use App\Modules\Cart\Entities\Cart;
use App\Modules\Coupon\Entities\CouponCustomization;
use App\Modules\Order\Entities\Order;
use App\Modules\Payment\Entities\Wallet;
use App\Modules\Permission\Traits\Permission\HasRoles;
use App\Modules\Product\Entities\Product;
use App\Services\Validation\ValidationError;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'lang',
        'verification_code',
        'is_verified',
        'image',
        'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function address()
    {
        return $this->addresses()->where('default', 0)->first();
    }

    public function compares()
    {
        return $this->belongsToMany(Product::class, 'product_wish', 'user_id', 'product_id')
            ->withPivot('type')
            ->wherePivot('type', 'compare')
            ->withTimestamps();
    }

    public function wishes()
    {
        return $this->belongsToMany(Product::class, 'product_wish', 'user_id', 'product_id')
            ->withPivot('type')
            ->wherePivot('type', 'wish')
            ->withTimestamps();
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function cart()
    {

        return $this->hasOne(Cart::class)->whereNull('ordered_at');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function coupons()
    {
        return $this->morphOne(CouponCustomization::class, 'model');
    }

       public function createAddressFromMap($addressObject)
    {
        if (!isset($addressObject['area']['city']['country'])) return new ValidationError("country  is Required.", 422); //country data
        if (!isset($addressObject['area']['city'])) return new ValidationError("city  is Required.", 422); //city data
        if (!isset($addressObject['area'])) return new ValidationError("city  is Required.", 422);
        //area  data
        
        $country = Country::where('name->en', $addressObject['area']['city']['country']['name'])->firstOr(function ()use($addressObject) {
            return Country::Create(
                [
                    'name' => [
                        'ar' => $addressObject['area']['city']['country']['name'],
                        'en' => $addressObject['area']['city']['country']['name'],
                    ]
                ]
            );
        });
        $city = City::where('name->en', $addressObject['area']['city']['name'])->firstOr(function () use ($addressObject,$country) {
            return City::Create(
                [
                    'country_id' => $country->id,
                    'name' => [
                        'ar' => $addressObject['area']['city']['name'],
                        'en' => $addressObject['area']['city']['name'],
                    ]
                ]
            );
        });
        $area =Area::where('name->en', $addressObject['area']['name'])->firstOr(function () use ($addressObject,$city)  {
            return Area::Create([
                    'city_id' => $city->id,
                    'name' => [
                        'ar' => $addressObject['area']['name'],
                        'en' => $addressObject['area']['name'],
                    ]
                ]
            );
        });
        $address = $this->addresses()->create([
            'area_id' => $area->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $addressObject['addressDetails']??'',
            'lat' => $addressObject['lat'],
            'lng' => $addressObject['lng'],
        ]);
        return $address;
    }

}
