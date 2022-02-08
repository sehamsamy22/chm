<?php

namespace App\Models;

use App\Models\Admin;
use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Occasion extends Model
{
    protected $fillable = ['name', 'description', 'date','type','user_id','isRecurring'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
