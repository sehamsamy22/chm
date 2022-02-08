<?php

namespace App\Modules\Warehouse\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'country', 'city', 'area', 'address', 'phone', 'manager', 'opening_time', 'closing_time','store_id'];
    public $translatable = ['name', 'country', 'city', 'area', 'address'];


}
