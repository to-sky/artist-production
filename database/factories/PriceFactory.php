<?php

use Faker\Generator as Faker;
use Nexmo\Account\Price;

$factory->define(Price::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['default', 'children', 'student']),
        'price' => $faker->randomFloat(2),
    ];
});
