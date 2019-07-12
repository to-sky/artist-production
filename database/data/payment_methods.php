<?php

return [
    [
        'name' => 'Банковский перевод',
        'active' => 1,
        'price_type' => \App\Models\PaymentMethod::PRICE_TYPE_PERCENTAGE,
        'price_amount' => 0,
        'payment_type' => \App\Models\PaymentMethod::TYPE_DELAY,
    ],
    [
        'name' => 'Paypal',
        'active' => 1,
        'price_type' => \App\Models\PaymentMethod::PRICE_TYPE_PERCENTAGE,
        'price_amount' => 3,
        'payment_type' => \App\Models\PaymentMethod::TYPE_INSTANT,
    ]
];
