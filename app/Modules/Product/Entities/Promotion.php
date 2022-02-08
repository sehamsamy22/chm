<?php

namespace App\Modules\Product\Entities;

use App\Traits\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name', 'discount', 'paid_quantity', 'discounted_quantity', 'start_date', 'expiration_date', 'deactivated_at', 'list_id'];

    public function list()
    {
        return $this->belongsTo(Lists::class,'list_id');
    }
}
