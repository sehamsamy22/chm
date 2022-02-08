<?php

namespace App\Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class Area extends Model
{
    use HasFactory,HasTranslations,SoftDeletes;

    protected $fillable = ['name','city_id','shipping_price'];
    public $translatable = ['name'];

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }

}
