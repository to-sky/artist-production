<?php

return [
    'api' => [
        'getAuth' => 'https://kassir.kartina.tv/LoginCommand.cmd',
        'getCities' => 'https://kassir.kartina.tv/GetCities.cmd',
        'getBuildings' => 'https://kassir.kartina.tv/GetBuildings.cmd',
        'getHalls' => 'https://kassir.kartina.tv/GetHalls.cmd',
        'getHallSchema' => 'https://kassir.kartina.tv/GetFlashHallDataCommand.cmd',
    ],
    'parser' => [
        'host' => 'https://biletkartina.tv',
        'startUrl' => 'https://biletkartina.tv/api/ru/event/getbyfilter'
    ]
];
