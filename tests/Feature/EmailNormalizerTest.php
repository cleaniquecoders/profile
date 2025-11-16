<?php

use CleaniqueCoders\Profile\Services\EmailNormalizer;

describe('EmailNormalizer', function () {
    it('normalizes email to lowercase', function () {
        expect(EmailNormalizer::normalize('User@Example.COM'))->toBe('user@example.com');
        expect(EmailNormalizer::normalize('JOHN.DOE@GMAIL.COM'))->toBe('john.doe@gmail.com');
    });

    it('removes dots from Gmail addresses when enabled', function () {
        expect(EmailNormalizer::normalize('john.doe@gmail.com', removeDots: true))->toBe('johndoe@gmail.com');
        expect(EmailNormalizer::normalize('j.o.h.n@gmail.com', removeDots: true))->toBe('john@gmail.com');
    });

    it('keeps dots for non-Gmail addresses', function () {
        expect(EmailNormalizer::normalize('john.doe@example.com', removeDots: true))->toBe('john.doe@example.com');
    });

    it('removes plus addressing when enabled', function () {
        expect(EmailNormalizer::normalize('user+tag@example.com', removePlusAddressing: true))->toBe('user@example.com');
        expect(EmailNormalizer::normalize('john+newsletter@example.com', removePlusAddressing: true))->toBe('john@example.com');
    });

    it('automatically removes plus addressing for Gmail', function () {
        expect(EmailNormalizer::normalize('user+tag@gmail.com'))->toBe('user@gmail.com');
        expect(EmailNormalizer::normalize('john.doe+newsletter@gmail.com'))->toBe('john.doe@gmail.com');
    });

    it('gets canonical form of email', function () {
        expect(EmailNormalizer::canonical('John.Doe+test@Gmail.COM'))->toBe('johndoe@gmail.com');
        expect(EmailNormalizer::canonical('user+tag@example.com'))->toBe('user@example.com');
    });

    it('extracts domain from email', function () {
        expect(EmailNormalizer::getDomain('user@example.com'))->toBe('example.com');
        expect(EmailNormalizer::getDomain('john@gmail.com'))->toBe('gmail.com');
        expect(EmailNormalizer::getDomain('invalid'))->toBeNull();
    });

    it('extracts local part from email', function () {
        expect(EmailNormalizer::getLocalPart('user@example.com'))->toBe('user');
        expect(EmailNormalizer::getLocalPart('john.doe@gmail.com'))->toBe('john.doe');
        expect(EmailNormalizer::getLocalPart('invalid'))->toBeNull();
    });

    it('checks if emails are equivalent', function () {
        expect(EmailNormalizer::areEquivalent('John.Doe@Gmail.COM', 'johndoe@gmail.com'))->toBeTrue();
        expect(EmailNormalizer::areEquivalent('user+tag@example.com', 'user@example.com'))->toBeTrue();
        expect(EmailNormalizer::areEquivalent('user@example.com', 'other@example.com'))->toBeFalse();
    });

    it('validates email format', function () {
        expect(EmailNormalizer::isValid('user@example.com'))->toBeTrue();
        expect(EmailNormalizer::isValid('john.doe+tag@gmail.com'))->toBeTrue();
        expect(EmailNormalizer::isValid('invalid'))->toBeFalse();
        expect(EmailNormalizer::isValid('invalid@'))->toBeFalse();
        expect(EmailNormalizer::isValid('@example.com'))->toBeFalse();
    });

    it('detects disposable email addresses', function () {
        expect(EmailNormalizer::isDisposable('user@mailinator.com'))->toBeTrue();
        expect(EmailNormalizer::isDisposable('test@guerrillamail.com'))->toBeTrue();
        expect(EmailNormalizer::isDisposable('user@gmail.com'))->toBeFalse();
        expect(EmailNormalizer::isDisposable('user@example.com'))->toBeFalse();
    });

    it('detects business emails', function () {
        expect(EmailNormalizer::isBusinessEmail('user@company.com'))->toBeTrue();
        expect(EmailNormalizer::isBusinessEmail('john@example.org'))->toBeTrue();
        expect(EmailNormalizer::isBusinessEmail('user@gmail.com'))->toBeFalse();
        expect(EmailNormalizer::isBusinessEmail('test@yahoo.com'))->toBeFalse();
        expect(EmailNormalizer::isBusinessEmail('user@hotmail.com'))->toBeFalse();
    });

    it('identifies email providers', function () {
        expect(EmailNormalizer::getProvider('user@gmail.com'))->toBe('Gmail');
        expect(EmailNormalizer::getProvider('user@googlemail.com'))->toBe('Gmail');
        expect(EmailNormalizer::getProvider('user@yahoo.com'))->toBe('Yahoo');
        expect(EmailNormalizer::getProvider('user@hotmail.com'))->toBe('Hotmail');
        expect(EmailNormalizer::getProvider('user@outlook.com'))->toBe('Outlook');
        expect(EmailNormalizer::getProvider('user@icloud.com'))->toBe('iCloud');
        expect(EmailNormalizer::getProvider('user@example.com'))->toBe('Other');
    });

    it('suggests corrections for common typos', function () {
        expect(EmailNormalizer::suggestCorrection('user@gmial.com'))->toBe('user@gmail.com');
        expect(EmailNormalizer::suggestCorrection('user@gmai.com'))->toBe('user@gmail.com');
        expect(EmailNormalizer::suggestCorrection('user@hotmial.com'))->toBe('user@hotmail.com');
        expect(EmailNormalizer::suggestCorrection('user@yahooo.com'))->toBe('user@yahoo.com');
        expect(EmailNormalizer::suggestCorrection('user@gmail.com'))->toBeNull();
    });

    it('handles edge cases', function () {
        expect(EmailNormalizer::normalize('  user@example.com  '))->toBe('user@example.com');
        expect(EmailNormalizer::normalize('USER+TAG@EXAMPLE.COM'))->toBe('user+tag@example.com');
    });
});
