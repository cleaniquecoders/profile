# Traits

The Profile package provides several traits that add profile functionality to your models through Laravel's polymorphic relationships.

## Available Traits

### HasProfile

The `HasProfile` trait is a convenience trait that includes all common profile traits.

**Location**: `CleaniqueCoders\Profile\Concerns\HasProfile`

**Includes**:

- `Addressable` - Manage addresses
- `Emailable` - Manage email addresses
- `Phoneable` - Manage phone numbers
- `Websiteable` - Manage websites

**Usage**:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\HasProfile;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasProfile;
}
```

**When to Use**:

- When your model needs all common profile information
- For user accounts, customer profiles, employee records
- When you want a complete profile solution

### Addressable

Adds the ability to manage multiple addresses.

**Location**: `CleaniqueCoders\Profile\Concerns\Addressable`

**Provides**:

- `addresses()` - MorphMany relationship to Address model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Addressable;

class Company extends Model
{
    use Addressable;
}

// Create an address
$company->addresses()->create([
    'primary' => '123 Business St',
    'city' => 'Kuala Lumpur',
    'postcode' => '50088',
    'country_id' => 1,
]);

// Get all addresses
$addresses = $company->addresses;
```

**When to Use**:

- Physical locations need to be tracked
- Billing and shipping addresses
- Office locations, branch addresses

### Emailable

Adds the ability to manage multiple email addresses.

**Location**: `CleaniqueCoders\Profile\Concerns\Emailable`

**Provides**:

- `emails()` - MorphMany relationship to Email model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Emailable;

class Contact extends Model
{
    use Emailable;
}

// Create an email
$contact->emails()->create([
    'email' => 'john@example.com',
    'is_default' => true,
]);

// Get all emails
$emails = $contact->emails;

// Get default email
$defaultEmail = $contact->emails()->where('is_default', true)->first();
```

**When to Use**:

- Multiple email addresses per entity
- Primary and secondary emails
- Work and personal emails

### Phoneable

Adds the ability to manage multiple phone numbers with types.

**Location**: `CleaniqueCoders\Profile\Concerns\Phoneable`

**Provides**:

- `phones()` - MorphMany relationship to Phone model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Phoneable;
use CleaniqueCoders\Profile\Models\PhoneType;

class Customer extends Model
{
    use Phoneable;
}

// Create phone numbers
$customer->phones()->create([
    'phone_number' => '+60123456789',
    'is_default' => true,
    'phone_type_id' => PhoneType::MOBILE,
]);

$customer->phones()->create([
    'phone_number' => '+60380001000',
    'is_default' => false,
    'phone_type_id' => PhoneType::OFFICE,
]);

// Get all phones
$phones = $customer->phones;

// Get mobile phones only
$mobilePhones = $customer->phones()->mobile()->get();

// Get home phones only
$homePhones = $customer->phones()->home()->get();

// Get office phones only
$officePhones = $customer->phones()->office()->get();
```

**When to Use**:

- Contact information with multiple numbers
- Different phone types (mobile, home, office, fax)
- Primary and backup phone numbers

**Available Phone Types**:

- `PhoneType::HOME` - Home phone
- `PhoneType::MOBILE` - Mobile/cell phone
- `PhoneType::OFFICE` - Office phone
- `PhoneType::OTHER` - Other phone type
- `PhoneType::FAX` - Fax number

### Websiteable

Adds the ability to manage multiple website URLs.

**Location**: `CleaniqueCoders\Profile\Concerns\Websiteable`

**Provides**:

- `websites()` - MorphMany relationship to Website model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Websiteable;

class Organization extends Model
{
    use Websiteable;
}

// Create websites
$organization->websites()->create([
    'url' => 'https://example.com',
    'is_default' => true,
]);

$organization->websites()->create([
    'url' => 'https://shop.example.com',
    'is_default' => false,
]);

// Get all websites
$websites = $organization->websites;

// Get default website
$defaultWebsite = $organization->websites()->where('is_default', true)->first();
```

**When to Use**:

- Company websites
- Social media profiles
- Portfolio links
- Multiple web properties

### Bankable

Adds the ability to manage bank account information.

**Location**: `CleaniqueCoders\Profile\Concerns\Bankable`

**Provides**:

- `banks()` - MorphMany relationship to BankAccount model
- `bank()` - BelongsTo relationship to Bank model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Bankable;

class Employee extends Model
{
    use Bankable;
}

// Create bank account
$employee->banks()->create([
    'bank_id' => 1,
    'account_number' => '1234567890',
    'account_holder_name' => 'John Doe',
]);

// Get all bank accounts
$bankAccounts = $employee->banks;

// Get with bank details
$accounts = $employee->banks()->with('bank')->get();
```

**When to Use**:

- Payroll information
- Payment processing
- Vendor payment details
- Direct deposit setup

## Combining Traits

You can use traits individually or in combination:

### Example 1: Basic Contact Information

```php
class Contact extends Model
{
    use Emailable, Phoneable;
}
```

### Example 2: Full Business Profile

```php
class Business extends Model
{
    use Addressable, Emailable, Phoneable, Websiteable, Bankable;
}

// Or simply:
class Business extends Model
{
    use HasProfile, Bankable;
}
```

### Example 3: Location Only

```php
class Store extends Model
{
    use Addressable, Phoneable;
}
```

## Trait Methods Summary

| Trait | Method | Return Type | Description |
|-------|--------|-------------|-------------|
| `Addressable` | `addresses()` | `MorphMany` | Get all addresses |
| `Emailable` | `emails()` | `MorphMany` | Get all emails |
| `Phoneable` | `phones()` | `MorphMany` | Get all phones |
| `Websiteable` | `websites()` | `MorphMany` | Get all websites |
| `Bankable` | `banks()` | `MorphMany` | Get all bank accounts |
| `Bankable` | `bank()` | `BelongsTo` | Get associated bank |
| `HasProfile` | - | - | Combines Addressable, Emailable, Phoneable, Websiteable |

## Best Practices

### 1. Choose the Right Traits

Only use the traits you need:

```php
// Don't do this if you only need addresses
use HasProfile;

// Do this instead
use Addressable;
```

### 2. Consider Your Domain

Match traits to your domain requirements:

```php
// For a physical store
class Store extends Model
{
    use Addressable, Phoneable, Websiteable;
}

// For an online service
class OnlineService extends Model
{
    use Emailable, Websiteable;
}

// For employee records
class Employee extends Model
{
    use HasProfile, Bankable;
}
```

### 3. Extend When Needed

Add custom methods to your models:

```php
class User extends Model
{
    use HasProfile;

    public function getPrimaryAddress()
    {
        return $this->addresses()->where('is_primary', true)->first();
    }

    public function getDefaultEmail()
    {
        return $this->emails()->where('is_default', true)->first()->email;
    }
}
```

## What's Next?

- [Models](04-models.md) - Detailed model documentation
- [Database Schema](05-database-schema.md) - Understanding the database structure
- [Usage Guides](../03-usage/README.md) - Practical usage examples
