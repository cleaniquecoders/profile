<?php

use CleaniqueCoders\Profile\Services\PhoneFormatter;

describe('PhoneFormatter', function () {
    it('formats phone to E.164 format', function () {
        expect(PhoneFormatter::toE164('0123456789', '60'))->toBe('+60123456789');
        expect(PhoneFormatter::toE164('123456789', '60'))->toBe('+60123456789');
        expect(PhoneFormatter::toE164('+60123456789', '60'))->toBe('+60123456789');
        expect(PhoneFormatter::toE164('60123456789', '60'))->toBe('+60123456789');
    });

    it('formats phone to national format', function () {
        expect(PhoneFormatter::toNational('+60123456789', '60'))->toBe('0123456789');
        expect(PhoneFormatter::toNational('60123456789', '60'))->toBe('0123456789');
        expect(PhoneFormatter::toNational('0123456789', '60'))->toBe('0123456789');
    });

    it('formats phone to international format without plus', function () {
        expect(PhoneFormatter::toInternational('+60123456789', '60'))->toBe('60123456789');
        expect(PhoneFormatter::toInternational('0123456789', '60'))->toBe('60123456789');
    });

    it('formats Malaysian phone to readable format', function () {
        expect(PhoneFormatter::toReadable('0123456789', '60'))->toBe('+60 12 345 6789');
    });

    it('formats US phone to readable format', function () {
        expect(PhoneFormatter::toReadable('2345678901', '1'))->toBe('+1 (234) 567-8901');
    });

    it('cleans phone number', function () {
        expect(PhoneFormatter::clean('+60 12-345 6789'))->toBe('+60123456789');
        expect(PhoneFormatter::clean('(012) 345-6789'))->toBe('0123456789');
    });

    it('validates Malaysian phone numbers', function () {
        expect(PhoneFormatter::isValid('0123456789', '60'))->toBeTrue();
        expect(PhoneFormatter::isValid('60123456789', '60'))->toBeTrue();
        expect(PhoneFormatter::isValid('123', '60'))->toBeFalse();
        expect(PhoneFormatter::isValid('012345678901234567890', '60'))->toBeFalse();
    });

    it('validates US phone numbers', function () {
        expect(PhoneFormatter::isValid('2345678901', '1'))->toBeTrue();
        expect(PhoneFormatter::isValid('12345678901', '1'))->toBeTrue();
        expect(PhoneFormatter::isValid('123', '1'))->toBeFalse();
    });

    it('detects country code from phone number', function () {
        expect(PhoneFormatter::detectCountryCode('60123456789'))->toBe('60');
        expect(PhoneFormatter::detectCountryCode('12345678901'))->toBe('1');
        expect(PhoneFormatter::detectCountryCode('447700900123'))->toBe('44');
    });

    it('standardizes phone numbers', function () {
        expect(PhoneFormatter::standardize('0123456789', '60'))->toBe('+60123456789');
        expect(PhoneFormatter::standardize('+60 12-345 6789', '60'))->toBe('+60123456789');
    });

    it('auto-detects country code when standardizing', function () {
        expect(PhoneFormatter::standardize('60123456789'))->toBe('+60123456789');
    });
});
