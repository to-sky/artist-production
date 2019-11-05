<?php

use App\Models\Building;
use App\Models\Hall;
use Faker\Generator as Faker;

$factory->define(Hall::class, function (Faker $faker) {
    return array(
        'name' => $faker->name,
        'accounting_code' => $faker->randomNumber(),
        'buildings_id' => function() {
            return Building::inRandomOrder()->first()->id;
        }
    );
});
