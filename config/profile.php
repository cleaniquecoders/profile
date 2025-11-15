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
        'social_media' => [
            'model' => \CleaniqueCoders\Profile\Models\SocialMedia::class,
            'type' => 'socialable',
        ],
        'emergency_contact' => [
            'model' => \CleaniqueCoders\Profile\Models\EmergencyContact::class,
            'type' => 'contactable',
        ],
        'credential' => [
            'model' => \CleaniqueCoders\Profile\Models\Credential::class,
            'type' => 'credentialable',
        ],
        'document' => [
            'model' => \CleaniqueCoders\Profile\Models\Document::class,
            'type' => 'documentable',
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
