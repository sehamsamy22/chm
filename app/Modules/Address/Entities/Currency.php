<?php

namespace App\Modules\Address\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'code','logo','symbol'];
    public $translatable = ['name'];
}
