<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Role::class, function (Faker $faker) {
    $name = $faker->word;
    return [
        'name' => $name,
        'display_name' => strtoupper($name),
        'description' => $faker->sentence(rand(3, 8))
    ];
});
