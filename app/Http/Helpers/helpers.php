<?php

function getsetting($name)
{
    $setting = \App\Modules\Setting\Entities\Setting::where('name', $name)->first();
    return $setting->value;
}

// function getRate($accessKey, $symbols)
// {
//     $ch = curl_init('http://data.fixer.io/api/latest?access_key=' . $accessKey . '&symbols=' . $symbols . '&format=1');
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     $json = curl_exec($ch); // ******************* get the JSON data:
//     curl_close($ch);
    
//     $conversionResult = json_decode($json, true);  //*******************  Decode JSON response:
//     return $conversionResult['rates'][$symbols]??1;
// }

// function exchangeRate($amount, $to)
// {
//     $accessKey = 'e19718fb4e14082003bee97e816cbb2e';
//     $currentCurrency = getsetting('defaultCurrency');
//     $currentCurrencyRateEUR = getRate($accessKey, $currentCurrency);    //******************* get current currency in EUR
//     $currentCurrencyRateTo = getRate($accessKey, $to);    //******************* get new currency in EUR
//     return round(($currentCurrencyRateTo * $amount) / $currentCurrencyRateEUR,3);
// }
function exchangeRate($amount, $to)
{
    if (!\App\Modules\Address\Entities\ExchangeRate::Where('currency', $to)->first()) $to = getsetting('defaultCurrency');
    $toEURRate = \App\Modules\Address\Entities\ExchangeRate::Where('currency', $to)->first()->rate;
    if (!$toEURRate) $toEURRate = \App\Modules\Address\Entities\ExchangeRate::Where('currency', getsetting('defaultCurrency'))->first()->rate;
    $fromEURRate = \App\Modules\Address\Entities\ExchangeRate::Where('currency', getsetting('defaultCurrency'))->first()->rate;
    $convertedAmount = ($toEURRate * $amount) / $fromEURRate;
    return $convertedAmount;
}


?>
