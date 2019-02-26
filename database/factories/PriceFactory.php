<?php

use App\Models\Price;
use Faker\Generator as Faker;

$factory->define(Price::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['default', 'children', 'student']),
        'price' => $faker->randomFloat(2, 10, 200)
    ];
});
