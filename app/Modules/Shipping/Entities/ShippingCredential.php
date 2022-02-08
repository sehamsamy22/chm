<?php

namespace App\Modules\Shipping\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCredential extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'default'];

}
