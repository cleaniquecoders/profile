<?php

use CleaniqueCoders\Profile\Services\AddressFormatter;

describe('AddressFormatter', function () {
    it('formats Malaysian postcode', function () {
        expect(AddressFormatter::standardizePostcode('12345', 'MY'))->toBe('12345');
        expect(AddressFormatter::standardizePostcode('1234', 'MY'))->toBe('01234');
        expect(AddressFormatter::standardizePostcode('123456', 'MY'))->toBe('12345');
    });

    it('formats US postcode', function () {
        expect(AddressFormatter::standardizePostcode('12345', 'US'))->toBe('12345');
        expect(AddressFormatter::standardizePostcode('123456789', 'US'))->toBe('12345-6789');
    });

    it('formats UK postcode', function () {
        expect(AddressFormatter::standardizePostcode('SW1A1AA', 'UK'))->toBe('SW1A 1AA');
        expect(AddressFormatter::standardizePostcode('SW1A 1AA', 'UK'))->toBe('SW1A 1AA');
    });

    it('formats Singapore postcode', function () {
        expect(AddressFormatter::standardizePostcode('123456', 'SG'))->toBe('123456');
        expect(AddressFormatter::standardizePostcode('12345', 'SG'))->toBe('012345');
    });

    it('formats Canadian postcode', function () {
        expect(AddressFormatter::standardizePostcode('A1A1A1', 'CA'))->toBe('A1A 1A1');
        expect(AddressFormatter::standardizePostcode('A1A 1A1', 'CA'))->toBe('A1A 1A1');
    });

    it('validates Malaysian postcode', function () {
        expect(AddressFormatter::isValidPostcode('12345', 'MY'))->toBeTrue();
        expect(AddressFormatter::isValidPostcode('1234', 'MY'))->toBeFalse();
        expect(AddressFormatter::isValidPostcode('123456', 'MY'))->toBeFalse();
    });

    it('validates US postcode', function () {
        expect(AddressFormatter::isValidPostcode('12345', 'US'))->toBeTrue();
        expect(AddressFormatter::isValidPostcode('12345-6789', 'US'))->toBeTrue();
        expect(AddressFormatter::isValidPostcode('1234', 'US'))->toBeFalse();
    });

    it('validates UK postcode', function () {
        expect(AddressFormatter::isValidPostcode('SW1A 1AA', 'UK'))->toBeTrue();
        expect(AddressFormatter::isValidPostcode('SW1A1AA', 'UK'))->toBeTrue();
        expect(AddressFormatter::isValidPostcode('12345', 'UK'))->toBeFalse();
    });

    it('standardizes address lines', function () {
        expect(AddressFormatter::standardizeAddressLine('123 main street'))->toBe('123 Main Street');
        expect(AddressFormatter::standardizeAddressLine('  apt   5b  '))->toBe('Apt 5B');
    });

    it('standardizes city names', function () {
        expect(AddressFormatter::standardizeCity('kuala lumpur'))->toBe('Kuala Lumpur');
        expect(AddressFormatter::standardizeCity('NEW YORK'))->toBe('New York');
    });

    it('standardizes state names', function () {
        expect(AddressFormatter::standardizeState('selangor'))->toBe('Selangor');
        expect(AddressFormatter::standardizeState('CALIFORNIA'))->toBe('California');
    });

    it('gets US state abbreviation', function () {
        expect(AddressFormatter::getStateAbbreviation('California'))->toBe('CA');
        expect(AddressFormatter::getStateAbbreviation('New York'))->toBe('NY');
        expect(AddressFormatter::getStateAbbreviation('Texas'))->toBe('TX');
        expect(AddressFormatter::getStateAbbreviation('Invalid'))->toBeNull();
    });

    it('gets US state full name from abbreviation', function () {
        expect(AddressFormatter::getStateFullName('CA'))->toBe('California');
        expect(AddressFormatter::getStateFullName('NY'))->toBe('New York');
        expect(AddressFormatter::getStateFullName('TX'))->toBe('Texas');
        expect(AddressFormatter::getStateFullName('ZZ'))->toBeNull();
    });

    it('standardizes full address', function () {
        $address = [
            'primary' => '123 main street',
            'secondary' => 'apt 5b',
            'city' => 'kuala lumpur',
            'state' => 'selangor',
            'postcode' => '12345',
            'country_code' => 'MY',
        ];

        $standardized = AddressFormatter::standardizeAddress($address);

        expect($standardized['primary'])->toBe('123 Main Street');
        expect($standardized['secondary'])->toBe('Apt 5B');
        expect($standardized['city'])->toBe('Kuala Lumpur');
        expect($standardized['state'])->toBe('Selangor');
        expect($standardized['postcode'])->toBe('12345');
    });
});
