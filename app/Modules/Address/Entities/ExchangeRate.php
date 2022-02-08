<?php

namespace App\Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class ExchangeRate extends Model
{
    use HasFactory;
    protected $fillable = ['currency','rate'];
}
