<?php

use Faker\Generator as Faker;

$factory->define(\CLNQCDRS\Email\Models\Email::class, function (Faker $faker) {
    return [
        'email' => $faker->companyEmail,
    ];
});
