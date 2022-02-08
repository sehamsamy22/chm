<?php

namespace App\Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class PaymentMethod extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = ['name', 'image', 'is_online', 'deactivated_at'];
    public $translatable = ['name'];

    public function scopeActive($query)
    {
        return $query->whereNull('deactivated_at');
    }

    public function credentials()
    {
        return $this->hasMany(PaymentCredential::class, 'payment_method_id');
    }
}
