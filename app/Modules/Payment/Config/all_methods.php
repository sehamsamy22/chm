<?php

return [
    'cash' => [
        'id' => 1,
        'name_ar' => 'دفع نقدي',
        'is_online' => 0,
        'active' => 1,
        'image' => '/storage/uploads/pYcCBn-1632299723.png'
    ],
    'tap' => [
        'id' => 2,
        'name_ar' => 'تاب',
        'is_online' => 1,
        'active' => 1,
        'image' => '/storage/uploads/EM7oE3-1632300032.jpg',
        'credentials' => [
            'secret_api_Key' => env('TAP_SECRET_API_KEY'),
            'merchant_id' => env('TAP_MERCHANT_ID'),
            'url' => 'https://api.tap.company/v2'
        ]
    ],

    'wallet' => [
        'id' => 3,
        'name_ar' => 'المحفظة',
        'is_online' => 0,
        'active' => 1,
        'image' => 'image-payment.png'
    ],
    'paypal' => [
        'id' => 8,
        'name_ar' => 'باى بال',
        'is_online' => 1,
        'active' => 1,
        'image' => '/storage/uploads/N6feoN-1632300073.png',
        'credentials' => [
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
            'mode' => env('PAYPAL_MODE')
        ],
    ],
    'moyasar' => [
        'id' => 9,
        'name_ar' => 'ميسير',
        'is_online' => 1,
        'active' => 1,
        'image' => '/storage/uploads/VIXE2A-1632228706.png',
        'credentials' => [
            'publish_key' => env('MOYASAR_PUBLISH_KEY'),
            'secret' => env('MOYASAR_SECRET_KEY')
        ]
    ],
    'stripe' => [
        'id' => 10,
        'name_ar' => 'استريب',
        'is_online' => 1,
        'active' => 1,
        'image' => '/storage/uploads/oSQ4c6-1632229139.png',
        'credentials' => [
            'stripe_key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET')
        ]
    ],
];

