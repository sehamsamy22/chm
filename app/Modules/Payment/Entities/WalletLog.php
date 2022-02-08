<?php

namespace App\Modules\Payment\Entities;

use App\Modules\Order\Entities\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletLog extends Model
{
    use HasFactory;

    protected $fillable = ['wallet_id', 'order_id', 'amount', 'status'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
