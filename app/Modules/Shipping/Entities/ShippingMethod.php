<?php

namespace App\Modules\Shipping\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ShippingMethod extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'active', 'deactivation_notes'];
    public $translatable = ['name'];

    public function credentials()
    {
        return $this->hasMany(ShippingCredential::class, 'method_id');
    }
}
