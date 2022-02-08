<?php

namespace App\Modules\Address\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class Address extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['user_id','area_id','name','phone','address','lat','lng', 'default'];
    public function area(){
        return $this->belongsTo(Area::class,'area_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
