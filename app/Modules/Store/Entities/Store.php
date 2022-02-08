<?php

namespace App\Modules\Store\Entities;

use App\Modules\Address\Entities\Country;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = ['name', 'description', 'logo', 'country_id'];
    public $translatable = ['name', 'description'];


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

}
