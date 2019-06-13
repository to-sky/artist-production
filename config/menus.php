<?php
return [
    'client' => [
        'links' => [
            [
                'name' => 'profile',
                'route' => 'profile.show',
                'label' => 'Profile',
            ],
            [
                'name' => 'orders',
                'route' => 'order.index',
                'label' => 'My Orders',
            ],
            [
                'name' => 'addresses',
                'route' => 'address.index',
                'label' => 'Addresses',
            ]
        ],
    ],
];