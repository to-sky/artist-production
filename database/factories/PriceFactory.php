<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Price::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['default', 'children', 'student']),
        'price' => $faker->randomFloat(2),
    ];
});
