<?php

namespace App\Modules\Payment\Methods;

use App\Modules\Order\Entities\Order;
use Illuminate\Http\Request;

abstract class PaymentContract
{
    protected $errorMessage;

    public function __construct()
    {
        $this->errorMessage = __('payment::methods.deactivate_error_message');
    }

    abstract public function checkAvailability();

    abstract public function initiate($request, $user);

    protected function response($message, $data, $code, bool $success = true): array
    {
        return [
            'message' => $message,
            'data' => $data,
            'status_code' => $code,
            'success' => $success
        ];
    }
}
