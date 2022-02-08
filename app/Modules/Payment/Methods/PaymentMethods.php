<?php
namespace App\Modules\Payment\Methods;

class PaymentMethods
{
    const CASH = 1;
    const TAP = 2;
    const WALLET = 3;
    const PAYPAL =8;
    const MOYASAR =9;
    const STRIPE =10;
    const TELR =11;
    public static function paymentClass($paymentMethod)
    {
        switch ($paymentMethod) {
            case self::CASH : return Cash::class; break;
            case self::TAP : return Tap::class; break;
            case self::WALLET : return Wallet::class; break;
            case self::PAYPAL : return PayPal::class; break;
            case self::MOYASAR : return Moyasar::class; break;
            case self:: STRIPE: return Stripe::class; break;
            case self:: TELR: return Telr::class; break;
            default : return false;
        }
    }
    public static function paymentId($paymentType)
    {
        switch ($paymentType) {
            case 'CASH' : return 1; break;
            case 'TAP' : return 2; break;
            case 'WALLET' : return 3; break;
            case 'PAYPAL' : return 8; break;
            case 'TELR' : return 11; break;
            default : return false;
        }
    }
}
