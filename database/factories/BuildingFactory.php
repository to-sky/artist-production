<?php

use App\Models\Building;
use App\Models\City;
use Faker\Generator as Faker;

$factory->define(Building::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'cities_id' => function() {
            return City::inRandomOrder()->first()->id;
        }
    ];
});
