# Configuration API

Complete reference for all configuration options in the Profile package.

## Configuration File

The configuration file is located at `config/profile.php`.

## Configuration Structure

```php
return [
    'providers' => [...],
    'seeders' => [...],
    'data' => [...],
];
```

## Providers

Defines the models and polymorphic type names used by the package.

### Structure

```php
'providers' => [
    'address' => [
        'model' => \CleaniqueCoders\Profile\Models\Address::class,
        'type' => 'addressable',
    ],
    // ... other providers
],
```

### Options

#### `address`

**Type**: `array`

Configure the address model and polymorphic type.

```php
'address' => [
    'model' => \CleaniqueCoders\Profile\Models\Address::class,
    'type' => 'addressable',
],
```

- `model` (string): Fully qualified class name of the Address model
- `type` (string): Polymorphic type name (creates `addressable_type` and `addressable_id` columns)

#### `email`

**Type**: `array`

Configure the email model and polymorphic type.

```php
'email' => [
    'model' => \CleaniqueCoders\Profile\Models\Email::class,
    'type' => 'emailable',
],
```

- `model` (string): Fully qualified class name of the Email model
- `type` (string): Polymorphic type name

#### `phone`

**Type**: `array`

Configure the phone model and polymorphic type.

```php
'phone' => [
    'model' => \CleaniqueCoders\Profile\Models\Phone::class,
    'type' => 'phoneable',
],
```

- `model` (string): Fully qualified class name of the Phone model
- `type` (string): Polymorphic type name

#### `phoneType`

**Type**: `array`

Configure the phone type model.

```php
'phoneType' => [
    'model' => \CleaniqueCoders\Profile\Models\PhoneType::class,
],
```

- `model` (string): Fully qualified class name of the PhoneType model

#### `website`

**Type**: `array`

Configure the website model and polymorphic type.

```php
'website' => [
    'model' => \CleaniqueCoders\Profile\Models\Website::class,
    'type' => 'websiteable',
],
```

- `model` (string): Fully qualified class name of the Website model
- `type` (string): Polymorphic type name

#### `bank`

**Type**: `array`

Configure the bank model and polymorphic type.

```php
'bank' => [
    'model' => \CleaniqueCoders\Profile\Models\Bank::class,
    'type' => 'bankable',
],
```

- `model` (string): Fully qualified class name of the Bank model
- `type` (string): Polymorphic type name

#### `country`

**Type**: `array`

Configure the country model.

```php
'country' => [
    'model' => \CleaniqueCoders\Profile\Models\Country::class,
],
```

- `model` (string): Fully qualified class name of the Country model

## Seeders

Defines which seeders should run when executing `php artisan profile:seed`.

### Structure

```php
'seeders' => [
    BankSeeder::class,
    CountrySeeder::class,
    PhoneTypeSeeder::class,
],
```

### Options

#### Default Seeders

- `BankSeeder::class` - Seeds bank data
- `CountrySeeder::class` - Seeds country data
- `PhoneTypeSeeder::class` - Seeds phone type data

### Custom Seeders

You can add your own seeders:

```php
'seeders' => [
    \CleaniqueCoders\Profile\Database\Seeders\BankSeeder::class,
    \CleaniqueCoders\Profile\Database\Seeders\CountrySeeder::class,
    \CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder::class,
    \App\Database\Seeders\CustomBankSeeder::class,
    \App\Database\Seeders\RegionalBankSeeder::class,
],
```

## Data

Configuration data used by seeders and the package.

### Structure

```php
'data' => [
    'phoneType' => [
        'Home',
        'Mobile',
        'Office',
        'Other',
        'Fax',
    ],
],
```

### Options

#### `phoneType`

**Type**: `array`

List of phone type names to be seeded.

```php
'phoneType' => [
    'Home',
    'Mobile',
    'Office',
    'Other',
    'Fax',
],
```

You can customize the phone types:

```php
'phoneType' => [
    'Home',
    'Mobile',
    'Office',
    'Work',
    'Personal',
    'Emergency',
    'Fax',
],
```

## Usage Examples

### Using Custom Models

```php
// config/profile.php
'providers' => [
    'address' => [
        'model' => \App\Models\CustomAddress::class,
        'type' => 'addressable',
    ],
],
```

```php
// app/Models/CustomAddress.php
namespace App\Models;

use CleaniqueCoders\Profile\Models\Address as BaseAddress;

class CustomAddress extends BaseAddress
{
    // Add custom methods or properties
    public function getFormattedAddress(): string
    {
        return "{$this->primary}, {$this->city}";
    }
}
```

### Environment-Specific Configuration

```php
'providers' => [
    'address' => [
        'model' => app()->environment('production')
            ? \App\Models\ProductionAddress::class
            : \CleaniqueCoders\Profile\Models\Address::class,
        'type' => 'addressable',
    ],
],
```

### Multiple Seeder Sets

```php
// config/profile.php
'seeders' => app()->environment('local') ? [
    \CleaniqueCoders\Profile\Database\Seeders\BankSeeder::class,
    \CleaniqueCoders\Profile\Database\Seeders\CountrySeeder::class,
    \CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder::class,
    \App\Database\Seeders\TestDataSeeder::class, // Only in local
] : [
    \CleaniqueCoders\Profile\Database\Seeders\BankSeeder::class,
    \CleaniqueCoders\Profile\Database\Seeders\CountrySeeder::class,
    \CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder::class,
],
```

## Accessing Configuration

### In Code

```php
// Get address model class
$addressModel = config('profile.providers.address.model');

// Get polymorphic type name
$addressType = config('profile.providers.address.type');

// Get phone types
$phoneTypes = config('profile.data.phoneType');

// Get all seeders
$seeders = config('profile.seeders');
```

### In Models

The package uses configuration internally:

```php
// In Addressable trait
public function addresses(): MorphMany
{
    return $this->morphMany(
        config('profile.providers.address.model'),
        config('profile.providers.address.type')
    );
}
```

## Configuration Best Practices

### 1. Publish Configuration for Customization

```bash
php artisan vendor:publish --provider="CleaniqueCoders\Profile\ProfileServiceProvider" --tag=config
```

### 2. Use Environment Variables

```php
// config/profile.php
'providers' => [
    'address' => [
        'model' => env('PROFILE_ADDRESS_MODEL', \CleaniqueCoders\Profile\Models\Address::class),
        'type' => env('PROFILE_ADDRESS_TYPE', 'addressable'),
    ],
],
```

### 3. Cache Configuration in Production

```bash
php artisan config:cache
```

### 4. Document Custom Changes

```php
// config/profile.php

/*
|--------------------------------------------------------------------------
| Custom Address Model
|--------------------------------------------------------------------------
|
| We use a custom address model to add geocoding capabilities
| and integration with our mapping service.
|
*/
'providers' => [
    'address' => [
        'model' => \App\Models\GeocodedAddress::class,
        'type' => 'addressable',
    ],
],
```

## Configuration Summary

| Option | Type | Purpose |
|--------|------|---------|
| `providers.*.model` | string | Model class to use |
| `providers.*.type` | string | Polymorphic type name |
| `seeders` | array | List of seeders to run |
| `data.phoneType` | array | Phone type names |
