<?php

use CleaniqueCoders\Profile\Enums\CredentialType;
use CleaniqueCoders\Profile\Enums\DocumentType;
use CleaniqueCoders\Profile\Enums\PhoneType;
use CleaniqueCoders\Profile\Enums\RelationshipType;
use CleaniqueCoders\Profile\Enums\SocialMediaPlatform;

// CredentialType Enum Tests
it('credential type has all cases', function () {
    $cases = CredentialType::cases();

    expect($cases)->toHaveCount(9)
        ->and(CredentialType::LICENSE)->toBeInstanceOf(CredentialType::class)
        ->and(CredentialType::CERTIFICATION)->toBeInstanceOf(CredentialType::class);
});

it('credential type returns correct labels', function () {
    expect(CredentialType::LICENSE->label())->toBe('License')
        ->and(CredentialType::CERTIFICATION->label())->toBe('Certification')
        ->and(CredentialType::DIPLOMA->label())->toBe('Diploma');
});

it('credential type returns correct descriptions', function () {
    expect(CredentialType::LICENSE->description())->toContain('license')
        ->and(CredentialType::DEGREE->description())->toContain('degree');
});

it('credential type can check if typically expires', function () {
    expect(CredentialType::LICENSE->typicallyExpires())->toBeTrue()
        ->and(CredentialType::DIPLOMA->typicallyExpires())->toBeFalse();
});

// DocumentType Enum Tests
it('document type has all cases', function () {
    $cases = DocumentType::cases();

    expect($cases)->toHaveCount(14)
        ->and(DocumentType::PASSPORT)->toBeInstanceOf(DocumentType::class)
        ->and(DocumentType::ID)->toBeInstanceOf(DocumentType::class);
});

it('document type returns correct labels', function () {
    expect(DocumentType::PASSPORT->label())->toBe('Passport')
        ->and(DocumentType::ID->label())->toBe('National ID Card')
        ->and(DocumentType::DRIVER_LICENSE->label())->toContain('License');
});

it('document type returns correct descriptions', function () {
    expect(DocumentType::PASSPORT->description())->toContain('Passport')
        ->and(DocumentType::VISA->description())->toContain('visa');
});

it('document type can check if typically expires', function () {
    expect(DocumentType::PASSPORT->typicallyExpires())->toBeTrue()
        ->and(DocumentType::RESUME->typicallyExpires())->toBeFalse();
});

it('document type returns typical extensions', function () {
    $extensions = DocumentType::PASSPORT->typicalExtensions();

    expect($extensions)->toBeArray()
        ->and($extensions)->toContain('pdf');
});

// PhoneType Enum Tests
it('phone type has all cases', function () {
    $cases = PhoneType::cases();

    expect($cases)->toHaveCount(5)
        ->and(PhoneType::HOME)->toBeInstanceOf(PhoneType::class)
        ->and(PhoneType::MOBILE)->toBeInstanceOf(PhoneType::class);
});

it('phone type returns correct labels', function () {
    expect(PhoneType::HOME->label())->toBe('Home')
        ->and(PhoneType::MOBILE->label())->toBe('Mobile')
        ->and(PhoneType::OFFICE->label())->toBe('Office');
});

it('phone type returns correct descriptions', function () {
    expect(PhoneType::HOME->description())->toContain('Home')
        ->and(PhoneType::MOBILE->description())->toContain('Mobile');
});

// RelationshipType Enum Tests
it('relationship type has all cases', function () {
    $cases = RelationshipType::cases();

    expect($cases)->toHaveCount(18)
        ->and(RelationshipType::SPOUSE)->toBeInstanceOf(RelationshipType::class)
        ->and(RelationshipType::PARENT)->toBeInstanceOf(RelationshipType::class);
});

it('relationship type returns correct labels', function () {
    expect(RelationshipType::SPOUSE->label())->toBe('Spouse')
        ->and(RelationshipType::MOTHER->label())->toBe('Mother')
        ->and(RelationshipType::FRIEND->label())->toBe('Friend');
});

it('relationship type returns correct descriptions', function () {
    expect(RelationshipType::SPOUSE->description())->toContain('spouse')
        ->and(RelationshipType::GUARDIAN->description())->toContain('guardian');
});

it('relationship type can check if is family', function () {
    expect(RelationshipType::SPOUSE->isFamily())->toBeTrue()
        ->and(RelationshipType::MOTHER->isFamily())->toBeTrue()
        ->and(RelationshipType::FRIEND->isFamily())->toBeFalse();
});

// SocialMediaPlatform Enum Tests
it('social media platform has all cases', function () {
    $cases = SocialMediaPlatform::cases();

    expect($cases)->toHaveCount(20)
        ->and(SocialMediaPlatform::FACEBOOK)->toBeInstanceOf(SocialMediaPlatform::class)
        ->and(SocialMediaPlatform::TWITTER)->toBeInstanceOf(SocialMediaPlatform::class);
});

it('social media platform returns correct labels', function () {
    expect(SocialMediaPlatform::FACEBOOK->label())->toBe('Facebook')
        ->and(SocialMediaPlatform::GITHUB->label())->toBe('GitHub')
        ->and(SocialMediaPlatform::LINKEDIN->label())->toBe('LinkedIn');
});

it('social media platform returns correct descriptions', function () {
    expect(SocialMediaPlatform::FACEBOOK->description())->toContain('Facebook')
        ->and(SocialMediaPlatform::YOUTUBE->description())->toContain('video');
});

it('social media platform returns URL patterns', function () {
    expect(SocialMediaPlatform::TWITTER->urlPattern())->toContain('twitter.com')
        ->and(SocialMediaPlatform::GITHUB->urlPattern())->toContain('github.com')
        ->and(SocialMediaPlatform::LINKEDIN->urlPattern())->toContain('linkedin.com');
});
