<?php

use Faker\Generator as Faker;

$factory->define(\CLNQCDRS\Profile\Models\Address::class, function (Faker $faker) {
    return [
        'country_id' => $faker->randomElement(range(1, 200)),
        'primary'    => $faker->streetName,
        'secondary'  => $faker->streetAddress,
        'postcode'   => $faker->postcode,
        'city'       => $faker->city,
        'state'      => $faker->state,
    ];
});
