<?php

namespace App\Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentCredential extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'default'];

}
