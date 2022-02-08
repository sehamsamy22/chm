<?php

namespace App\Exports;

use App\Models\User;
use App\Modules\Order\Entities\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return ['id', 'user', 'address', 'method', 'status', 'total', 'unique_id', 'created_at'];
    }

    /**
     * @return array
     */
    public function collection()
    {
        $orders = Order::select(['id', 'user_id', 'address_id', 'method_id', 'status', 'total', 'unique_id', 'created_at'])->get();
        return $orders->map(function ($order) {
            return [
                $order->id,
                $order->user->name ?? '',
                $order->address->area->name ?? '',
                $order->method->name ?? '',
                $order->status,
                $order->total,
                $order->unique_id,
                $order->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
