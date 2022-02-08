<?php

namespace App\Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $casts = [
        'order_details' => 'json',
        'transaction_response' => 'json'
    ];
    protected $fillable = ['user_id', 'order_details', 'payment_reference', 'card_info', 'transaction_response', 'response_code', 'transaction_status', 'total_amount'];
    // public function model()
    // {
    //     return $this->morphTo();
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->hasOne(Order::class,"transaction_id");
    }
}
