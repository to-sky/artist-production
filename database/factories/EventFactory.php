<?php

use App\Models\Building;
use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'date' => $faker->date().' '.$faker->time('H:i'),
        'is_active' => $faker->boolean,
        'ticket_refund_period' => $faker->numberBetween(0, 5),
        'buildings_id' => function() {
            return Building::inRandomOrder()->first()->id;
        }
    ];
});
