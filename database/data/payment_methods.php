<?php

return [
    [
        'name' => 'Bank',
        'active' => 1,
        'price_type' => \App\Models\PaymentMethod::PRICE_TYPE_PERCENTAGE,
        'price_amount' => 0,
    ],
    [
        'name' => 'Paypal',
        'active' => 1,
        'price_type' => \App\Models\PaymentMethod::PRICE_TYPE_PERCENTAGE,
        'price_amount' => 3,
    ]
];