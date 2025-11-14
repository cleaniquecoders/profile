# Configuration

Learn how to configure the Profile package to suit your application's needs.

## Configuration File

The configuration file is located at `config/profile.php`. If you need to customize it, publish the configuration file:

```bash
php artisan vendor:publish --provider="CleaniqueCoders\Profile\ProfileServiceProvider" --tag=config
```

## Configuration Options

### Providers

The `providers` array defines the models and their polymorphic type names:

```php
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
```

### Custom Models

You can replace the default models with your own:

```php
'providers' => [
    'address' => [
        'model' => \App\Models\CustomAddress::class,
        'type' => 'addressable',
    ],
    // ... other providers
],
```

Ensure your custom models extend the package's base models or implement the same functionality.

### Seeders

Define which seeders should run when executing `php artisan profile:seed`:

```php
'seeders' => [
    BankSeeder::class,
    CountrySeeder::class,
    PhoneTypeSeeder::class,
],
```

### Custom Seeders

You can add your own seeders:

```php
'seeders' => [
    \CleaniqueCoders\Profile\Database\Seeders\BankSeeder::class,
    \CleaniqueCoders\Profile\Database\Seeders\CountrySeeder::class,
    \CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder::class,
    \App\Database\Seeders\CustomBankSeeder::class, // Your custom seeder
],
```

### Phone Types Data

Configure the available phone types:

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

## Environment-Specific Configuration

You can create environment-specific configurations by checking the environment:

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

## What's Next?

- [Quick Start](03-quick-start.md) - Start using the package with basic examples
- [Architecture Overview](../02-architecture/01-overview.md) - Understand the package architecture
