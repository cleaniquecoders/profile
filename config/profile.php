<?php

use CleaniqueCoders\Profile\Database\Seeders\BankSeeder;
use CleaniqueCoders\Profile\Database\Seeders\CountrySeeder;
use CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder;

return [
    'providers' => [
        'address' => [
            'model' => \CleaniqueCoders\Profile\Models\Address::class,
            'type' => 'addressable',
        ],
        'email' => [
            'model' => \CleaniqueCoders\Profile\Models\Email::class,
            'type' => 'emailable',
        ],
        'bank' => [
            'model' => \CleaniqueCoders\Profile\Models\Bank::class,
            'type' => 'bankable',
        ],
        'phone' => [
            'model' => \CleaniqueCoders\Profile\Models\Phone::class,
            'type' => 'phoneable',
        ],
        'phoneType' => [
            'model' => \CleaniqueCoders\Profile\Models\PhoneType::class,
        ],
        'country' => [
            'model' => \CleaniqueCoders\Profile\Models\Country::class,
        ],
        'website' => [
            'model' => \CleaniqueCoders\Profile\Models\Website::class,
            'type' => 'websiteable',
        ],
    ],
    'seeders' => [
        BankSeeder::class,
        CountrySeeder::class,
        PhoneTypeSeeder::class, // @todo should use enum
    ],
    'data' => [
        'phoneType' => [
            'Home',
            'Mobile',
            'Office',
            'Other',
            'Fax',
        ],
    ],
];
