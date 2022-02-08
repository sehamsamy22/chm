<?php
return [
    'client_id' => env('PAYPAL_CLIENT_ID','AXH_rg1l30bR_Kvt6LMRn1IxpT0xsEBh2QD_fEEdrY1uY8QVo35PkqYhmOoLEI6LYz9Z6Fr2GLf448SS'),
    'secret' => env('PAYPAL_SECRET','EKpfNcAzuEWBTahtp7qI7dQAxIOxGhe6WF3KcP4QnylArM5HeQKv_3wo0gh2FjlRmIVgX_hnk2NVOjhX'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];
