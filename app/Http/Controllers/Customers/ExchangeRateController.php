<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Modules\Address\Entities\ExchangeRate;
use Illuminate\Http\Request;


class ExchangeRateController extends Controller
{
    public function ExchangeRate(Request $request)
    {
        $request = $request->validate([
            'amount' => 'required',
            'to' => 'required|string',
            'from' => 'required|string',
        ]);
        $fromEURRate = ExchangeRate::Where('currency', $request['from'])->first()->rate;
        $toEURRate = ExchangeRate::Where('currency', $request['to'])->first()->rate;
        $convertedAmount = ($toEURRate * $request['amount']) / $fromEURRate;
        return $this->apiResponse($convertedAmount);
    }

}
