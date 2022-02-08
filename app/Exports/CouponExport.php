<?php

namespace App\Exports;

use App\Models\User;
use App\Modules\Coupon\Entities\Coupon;
use App\Modules\Order\Entities\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CouponExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return ['id', 'name', 'prom_code', 'type', 'start_date', 'end_date', 'prom_time', 'used_count', 'max_limit', 'amount', 'created_at'];
    }

    /**
     * @return array
     */
    public function collection()
    {
        $coupons = Coupon::select(['id', 'name', 'prom_code', 'type', 'start_date', 'end_date', 'prom_time', 'used_count', 'max_limit', 'amount', 'created_at'])->get();
        return $coupons->map(function ($order) {
            return [
                $order->id,
                $order->name ?? '',
                $order->prom_code ?? '',
                $order->type ?? '',
                $order->start_date,
                $order->end_date,
                $order->used_count,
                $order->max_limit,
                $order->amount,
                $order->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
