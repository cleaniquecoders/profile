<?php

return [
    'providers' => [
        'address' => [
            'model' => \CleaniqueCoders\Profile\Models\Address::class,
            'type'  => 'addressable',
        ],
        'email' => [
            'model' => \CleaniqueCoders\Profile\Models\Email::class,
            'type'  => 'emailable',
        ],
        'bank' => [
            'model' => \CleaniqueCoders\Profile\Models\Bank::class,
            'type'  => 'bankable',
        ],
        'phone' => [
            'model' => \CleaniqueCoders\Profile\Models\Phone::class,
            'type'  => 'phoneable',
        ],
        'phoneType' => [
            'model' => \CleaniqueCoders\Profile\Models\PhoneType::class,
        ],
        'website' => [
            'model' => \CleaniqueCoders\Profile\Models\Website::class,
            'type'  => 'websiteable',
        ],
    ],
    'seeders' => [
        BankSeeder::class,
        CountrySeeder::class,
        PhoneTypeSeeder::class,
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
