# Quick Start

Get started quickly with the Profile package using practical examples.

## Basic Setup

### 1. Add the HasProfile Trait

Add the `HasProfile` trait to any model that needs profile information:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\HasProfile;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasProfile;
}
```

The `HasProfile` trait includes:

- `Addressable` - Manage addresses
- `Emailable` - Manage email addresses
- `Phoneable` - Manage phone numbers
- `Websiteable` - Manage websites

### 2. Create Profile Records

#### Adding an Address

```php
use App\Models\User;
use CleaniqueCoders\Profile\Models\Country;

$user = User::find(1);

$user->addresses()->create([
    'primary' => '9 miles, Sungei Way',
    'secondary' => 'P.O.Box 6503, Seri Setia',
    'city' => 'Petaling Jaya',
    'postcode' => '46150',
    'state' => 'Selangor',
    'country_id' => Country::where('name', 'Malaysia')->first()->id
]);
```

#### Adding Phone Numbers

```php
use CleaniqueCoders\Profile\Models\PhoneType;

// Home phone
$user->phones()->create([
    'phone_number' => '+6089259167',
    'is_default' => true,
    'phone_type_id' => PhoneType::HOME,
]);

// Mobile phone
$user->phones()->create([
    'phone_number' => '+60191234567',
    'is_default' => false,
    'phone_type_id' => PhoneType::MOBILE,
]);

// Office phone
$user->phones()->create([
    'phone_number' => '+60380001000',
    'is_default' => false,
    'phone_type_id' => PhoneType::OFFICE,
]);
```

#### Adding Emails

```php
$user->emails()->create([
    'email' => 'john.doe@example.com',
    'is_default' => true,
]);

$user->emails()->create([
    'email' => 'john.work@company.com',
    'is_default' => false,
]);
```

#### Adding Websites

```php
$user->websites()->create([
    'url' => 'https://example.com',
    'is_default' => true,
]);
```

### 3. Retrieve Profile Information

#### Get All Records

```php
// Get all addresses
$addresses = $user->addresses;

// Get all emails
$emails = $user->emails;

// Get all phones
$phones = $user->phones;

// Get all websites
$websites = $user->websites;
```

#### Query Specific Records

```php
// Get the default email
$defaultEmail = $user->emails()->where('is_default', true)->first();

// Get home phone number
$homePhone = $user->phones()->home()->first();

// Get all mobile phone numbers
$mobilePhones = $user->phones()->mobile()->get();

// Get office phones
$officePhones = $user->phones()->office()->get();
```

## Using Individual Traits

If you only need specific functionality, use individual traits instead of `HasProfile`:

### Addressable Only

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\Addressable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Addressable;
}
```

### Multiple Traits

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\Addressable;
use CleaniqueCoders\Profile\Concerns\Phoneable;
use CleaniqueCoders\Profile\Concerns\Emailable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Addressable, Phoneable, Emailable;
}
```

## Common Use Cases

### Corporate Entity with Full Profile

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\HasProfile;
use CleaniqueCoders\Profile\Concerns\Bankable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasProfile, Bankable;
}

// Usage
$company = Company::create(['name' => 'Acme Corporation']);

$company->addresses()->create([
    'primary' => 'Suite 100, Tech Tower',
    'city' => 'Kuala Lumpur',
    'postcode' => '50088',
    'state' => 'Federal Territory',
    'country_id' => Country::where('code', 'MY')->first()->id
]);

$company->phones()->create([
    'phone_number' => '+60380001000',
    'is_default' => true,
    'phone_type_id' => PhoneType::OFFICE,
]);

$company->emails()->create([
    'email' => 'info@acme.com',
    'is_default' => true,
]);
```

### Employee with Banking Information

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\HasProfile;
use CleaniqueCoders\Profile\Concerns\Bankable;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasProfile, Bankable;
}

// Usage
$employee = Employee::find(1);

$employee->banks()->create([
    'bank_id' => Bank::where('name', 'Maybank')->first()->id,
    'account_number' => '1234567890',
    'account_holder_name' => 'John Doe',
]);
```

## What's Next?

- [Architecture Overview](../02-architecture/01-overview.md) - Understand how the package works
- [Usage Guides](../03-usage/README.md) - Detailed guides for each trait
- [API Reference](../04-api-reference/README.md) - Complete API documentation
