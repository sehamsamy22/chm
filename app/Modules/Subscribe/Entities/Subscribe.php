<?php

namespace App\Modules\Subscribe\Entities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Subscribe extends Model
{
    use HasFactory;
    protected $fillable = ['email'];
    protected $table='subscribers';
}
