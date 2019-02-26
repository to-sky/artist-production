<?php

use App\Models\Hall;
use App\Models\Place;
use App\Models\Zone;
use Faker\Generator as Faker;

$factory->define(Place::class, function (Faker $faker) {
    return [
        'row_num' => $faker->numberBetween(1, 20),
        'place_num' => $faker->numberBetween(1, 30),
        'place_text' => $faker->sentence(),
        'help_text' => $faker->sentence(),
        'hall_id' => function() {
            return Hall::inRandomOrder()->first()->id;
        },
        'zone_id' => function() {
            return Zone::inRandomOrder()->first()->id;
        }
    ];
});
