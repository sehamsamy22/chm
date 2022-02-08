<?php

namespace App\Modules\Order\Entities;

use App\Modules\Order\Database\factories\OrderInvoiceLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInvoiceLog extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return OrderInvoiceLogFactory::new();
    }
}
