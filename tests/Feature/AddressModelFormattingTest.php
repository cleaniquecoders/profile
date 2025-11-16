<?php

use CleaniqueCoders\Profile\Models\Address;
use CleaniqueCoders\Profile\Models\Country;
use Illuminate\Support\Facades\Artisan;
use Workbench\App\Models\User;

beforeEach(function () {
    Artisan::call('profile:seed');
    $this->user = User::factory()->create();
    $this->country = Country::where('code', 'MY')->first() ?? Country::first();
});

describe('Address Model Formatting', function () {
    it('standardizes address', function () {
        $address = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 main street',
            'secondary' => 'apt 5b',
            'city' => 'kuala lumpur',
            'state' => 'selangor',
            'postcode' => '12345',
            'country_id' => $this->country->id,
        ]);

        $address->standardize();

        expect($address->primary)->toBe('123 Main Street');
        expect($address->secondary)->toBe('Apt 5B');
        expect($address->city)->toBe('Kuala Lumpur');
        expect($address->state)->toBe('Selangor');
        expect($address->postcode)->toBe('12345');
    });

    it('gets formatted postcode', function () {
        $address = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '1234',
            'country_id' => $this->country->id,
        ]);

        expect($address->getFormattedPostcode())->toBe('01234');
    });

    it('standardizes address with different country', function () {
        $usCountry = Country::where('code', 'US')->first();

        if (! $usCountry) {
            $usCountry = Country::create([
                'code' => 'US',
                'name' => 'United States',
            ]);
        }

        $address = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 main street',
            'city' => 'new york',
            'state' => 'new york',
            'postcode' => '123456789',
            'country_id' => $usCountry->id,
        ]);

        $address->standardize();

        expect($address->primary)->toBe('123 Main Street');
        expect($address->city)->toBe('New York');
        expect($address->state)->toBe('New York');
        expect($address->postcode)->toBe('12345-6789');
    });
});
