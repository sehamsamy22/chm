<?php

namespace App\Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = ['name','shipping_price','country_id'];
    public $translatable = ['name'];

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }

    public function areas(){
        return $this->hasMany(Area::class);
    }
}
