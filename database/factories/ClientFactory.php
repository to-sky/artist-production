<?php

use App\Models\Client;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'type' => $faker->numberBetween(0, 1),
        'commission' => 10,
        'user_id' => function() {
            return User::inRandomOrder()->first()->id;
        }
    ];
});
