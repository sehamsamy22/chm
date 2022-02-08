<?php

namespace App\Modules\Address\Entities;

use App\Modules\Store\Entities\Store;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = ['name','currency_id','flag','code'];
    public $translatable = ['name'];

    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }
    public function stores(){
        return $this->hasMany(Store::class,'country_id');
    }
}
