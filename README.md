# Profile

A Laravel package for managing profile information (addresses, emails, phone numbers, websites, and bank accounts) using polymorphic relationships.

[![Latest Stable Version](https://poser.pugx.org/cleaniquecoders/profile/v/stable)](https://packagist.org/packages/cleaniquecoders/profile) [![Total Downloads](https://poser.pugx.org/cleaniquecoders/profile/downloads)](https://packagist.org/packages/cleaniquecoders/profile) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cleaniquecoders/profile/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cleaniquecoders/profile/?branch=master) [![License](https://poser.pugx.org/cleaniquecoders/profile/license)](https://packagist.org/packages/cleaniquecoders/profile)

## Features

- **Polymorphic Design** - Reusable profile tables for any model
- **Trait-Based** - Use only what you need (addresses, emails, phones, websites, bank accounts)
- **Type-Safe** - Query scopes for phone types and other filters
- **Configurable** - Customize models and polymorphic type names
- **UUID Support** - Unique identifiers for external integrations
- **Soft Deletes** - Maintain audit trail of changes

## Requirements

- PHP ^8.3 | ^8.4
- Laravel ^11.0 | ^12.0

## Quick Start

### Installation

```bash
composer require cleaniquecoders/profile
php artisan vendor:publish --tag=profile-migrations
php artisan migrate
php artisan profile:seed
```

### Basic Usage

Add the `HasProfile` trait to your model:

```php
use CleaniqueCoders\Profile\Concerns\HasProfile;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasProfile;
}
```

Create profile information:

```php
use CleaniqueCoders\Profile\Models\PhoneType;

// Create address
$user->addresses()->create([
    'primary' => '123 Main Street',
    'city' => 'Kuala Lumpur',
    'postcode' => '50088',
    'country_id' => 1,
]);

// Create phone numbers
$user->phones()->create([
    'phone_number' => '+60123456789',
    'phone_type_id' => PhoneType::MOBILE,
    'is_default' => true,
]);

// Create email
$user->emails()->create([
    'email' => 'john@example.com',
    'is_default' => true,
]);

// Create website
$user->websites()->create([
    'url' => 'https://example.com',
    'is_default' => true,
]);
```

Query profile information:

```php
// Get all addresses
$addresses = $user->addresses;

// Get mobile phones only
$mobilePhones = $user->phones()->mobile()->get();

// Get default email
$email = $user->emails()->where('is_default', true)->first();
```

## Available Traits

| Trait | Purpose |
|-------|---------|
| `HasProfile` | Includes Addressable, Emailable, Phoneable, Websiteable |
| `Addressable` | Manage physical addresses |
| `Emailable` | Manage email addresses |
| `Phoneable` | Manage phone numbers (with types: home, mobile, office, fax, other) |
| `Websiteable` | Manage website URLs |
| `Bankable` | Manage bank account information |

Use individual traits for specific needs:

```php
use CleaniqueCoders\Profile\Concerns\Addressable;
use CleaniqueCoders\Profile\Concerns\Phoneable;

class Company extends Model
{
    use Addressable, Phoneable;
}
```

## Documentation

📚 **[Complete Documentation](docs/)** - Comprehensive guides and API reference

### Quick Links

- [Installation Guide](docs/01-getting-started/01-installation.md)
- [Configuration](docs/01-getting-started/02-configuration.md)
- [Quick Start Examples](docs/01-getting-started/03-quick-start.md)
- [Architecture Overview](docs/02-architecture/01-overview.md)
- [Usage Guides](docs/03-usage/)
- [API Reference](docs/04-api-reference/)
- [Best Practices](docs/03-usage/07-best-practices.md)

## Use Cases

### Corporate Profiles

```php
class Company extends Model
{
    use HasProfile, Bankable;
}

// Headquarters address
$company->addresses()->create([...]);

// Contact information
$company->phones()->create(['phone_type_id' => PhoneType::OFFICE, ...]);
$company->emails()->create(['email' => 'info@company.com', ...]);
$company->websites()->create(['url' => 'https://company.com', ...]);

// Banking details
$company->banks()->create([...]);
```

### Employee Management

```php
class Employee extends Model
{
    use HasProfile, Bankable;
}

// Home address for shipping
$employee->addresses()->create([...]);

// Multiple contact numbers
$employee->phones()->create(['phone_type_id' => PhoneType::MOBILE, ...]);
$employee->phones()->create(['phone_type_id' => PhoneType::HOME, ...]);

// Payroll bank account
$employee->banks()->create([...]);
```

### Customer Records

```php
class Customer extends Model
{
    use HasProfile;
}

// Billing and shipping addresses
$customer->addresses()->create(['type' => 'billing', ...]);
$customer->addresses()->create(['type' => 'shipping', ...]);

// Multiple contact methods
$customer->emails()->create([...]);
$customer->phones()->mobile()->create([...]);
```

## Testing

```bash
composer test
```

## Contributing

Contributions are welcome! Please see our [contribution guidelines](CONTRIBUTING.md) for details.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.txt).
