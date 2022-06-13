<?php

namespace App\Modules\Subscription\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionDayCount extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['count'];
    protected $fillable = ['count'];


}
