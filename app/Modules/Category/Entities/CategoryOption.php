<?php

namespace App\Modules\Category\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOption extends Model
{
    use HasFactory;
    protected $table = 'category_option';
    protected $fillable = ['category_id', 'option_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->with('categoryOptions');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id')->with('values');
    }
}
