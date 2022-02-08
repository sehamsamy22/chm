<?php

namespace App\Modules\Ad\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class AdLocation extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

}
