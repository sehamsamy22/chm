<?php
return [
    // The current mode is live|production or test
    'test_mode' => env('TELR_TEST_MODE', true),

    // The currency of store

    'currency' => 'SAR',

    // The sale endpoint that receive the params
    // @see https://telr.com/support/knowledge-base/hosted-payment-page-integration-guide
    'sale' => [
        'endpoint' => 'https://secure.telr.com/gateway/order.json',
    ],

    // The hosted payment page use the following params as it explained in the integration guide
    // @see https://telr.com/support/knowledge-base/hosted-payment-page-integration-guide/#request-method-and-format
   'create' => [
        'ivp_method' => "create",
        'ivp_store' =>'25748',
        'ivp_authkey' => 'g37LC@MnNZ#x9k6r',
        'return_auth' => 'https://chm.sa/payment?status=true',
        'return_can' => 'https://chm.sa/payment/cancel',
        'return_decl' => 'https://chm.sa/payment?status=false',
    ]
];