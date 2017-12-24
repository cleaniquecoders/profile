<?php

use Faker\Generator as Faker;

$factory->define(\CLNQCDRS\Profile\Models\Email::class, function (Faker $faker) {
    return [
        'email' => $faker->companyEmail,
    ];
});
