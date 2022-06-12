<?php

namespace App\Modules\Subscription\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionDeliveryCount extends Model
{
    use HasFactory;

    protected $fillable = ['count','type_id'];

    public function type()
    {
        return $this->belongsTo(SubscriptionType::class, 'type_id');
    }
}
