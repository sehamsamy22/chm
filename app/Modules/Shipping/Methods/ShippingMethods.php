<?php

namespace App\Modules\Shipping\Methods;

class ShippingMethods
{
    const ARAMEX = 1;
    const FASTLO = 2;
    public static function shippingClass($shippingMethod)
    {
        switch ($shippingMethod) {
            case self::ARAMEX :return Aramex::class;break;
            case self::FASTLO :return Fastlo::class;break;
            default :
                return false;
        }
    }
}
