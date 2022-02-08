<?php

namespace App\Modules\Ad\Entities;

use App\Scopes\AdStoreScope;
use App\Scopes\ListStoreScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Translatable\HasTranslations;

class Ad extends Model
{
    use HasFactory, SoftDeletes,HasTranslations;
    public $translatable = ['title', 'description'];
    protected $fillable = ['model_id', 'model_type', 'order', 'image', 'start_date', 'deactivated_at',
        'expired_at','external_url','location_id','store_id','title','description','store_id'];
    protected static function booted()
    {
        static::addGlobalScope(new AdStoreScope());
    }
    public function model()
    {
        return $this->morphTo('model')->withDefault();
    }
    public function location()
    {
        return $this->belongsTo(AdLocation::class, 'location_id');
    }

}
