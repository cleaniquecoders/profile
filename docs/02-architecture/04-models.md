# Models

The Profile package includes several Eloquent models that handle profile data storage and relationships.

## Core Models

### Address

Stores physical address information for any entity.

**Location**: `CleaniqueCoders\Profile\Models\Address`

**Traits**:

- `InteractsWithUuid` - Uses UUID for primary key

**Attributes**:

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | UUID | Primary key |
| `addressable_type` | string | Polymorphic type |
| `addressable_id` | bigint | Polymorphic ID |
| `primary` | string | Primary address line |
| `secondary` | string | Secondary address line (optional) |
| `city` | string | City name |
| `postcode` | string | Postal/ZIP code |
| `state` | string | State/province |
| `country_id` | bigint | Foreign key to countries table |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Relationships**:

```php
// Get the owning entity (User, Company, etc.)
$address->addressable(); // MorphTo

// Get the country
$address->country(); // BelongsTo
```

**Usage Example**:

```php
use CleaniqueCoders\Profile\Models\Address;

// Query addresses
$addresses = Address::where('city', 'Kuala Lumpur')->get();

// Access related entities
$address = Address::find(1);
$owner = $address->addressable; // User, Company, etc.
$country = $address->country;
```

### Email

Stores email addresses for any entity.

**Location**: `CleaniqueCoders\Profile\Models\Email`

**Traits**:

- `InteractsWithUuid` - Uses UUID for primary key

**Attributes**:

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | UUID | Primary key |
| `emailable_type` | string | Polymorphic type |
| `emailable_id` | bigint | Polymorphic ID |
| `email` | string | Email address |
| `is_default` | boolean | Whether this is the default email |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Relationships**:

```php
// Get the owning entity
$email->emailable(); // MorphTo
```

**Usage Example**:

```php
use CleaniqueCoders\Profile\Models\Email;

// Find by email address
$email = Email::where('email', 'john@example.com')->first();
$owner = $email->emailable;

// Get all default emails
$defaultEmails = Email::where('is_default', true)->get();
```

### Phone

Stores phone numbers with type classification.

**Location**: `CleaniqueCoders\Profile\Models\Phone`

**Traits**:

- `InteractsWithUuid` - Uses UUID for primary key

**Attributes**:

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | UUID | Primary key |
| `phoneable_type` | string | Polymorphic type |
| `phoneable_id` | bigint | Polymorphic ID |
| `phone_number` | string | Phone number |
| `phone_type_id` | bigint | Foreign key to phone_types table |
| `is_default` | boolean | Whether this is the default phone |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Relationships**:

```php
// Get the owning entity
$phone->phoneable(); // MorphTo

// Get the phone type
$phone->type(); // BelongsTo
```

**Query Scopes**:

```php
// Get home phones
Phone::home()->get();
$user->phones()->home()->get();

// Get mobile phones
Phone::mobile()->get();
$user->phones()->mobile()->get();

// Get office phones
Phone::office()->get();
$user->phones()->office()->get();

// Get other phones
Phone::other()->get();
$user->phones()->other()->get();

// Get fax numbers
Phone::fax()->get();
$user->phones()->fax()->get();
```

**Usage Example**:

```php
use CleaniqueCoders\Profile\Models\Phone;

// Query by phone type
$mobilePhones = Phone::mobile()->get();

// Access relationships
$phone = Phone::find(1);
$owner = $phone->phoneable;
$phoneType = $phone->type;
```

### PhoneType

Reference table for phone number types.

**Location**: `CleaniqueCoders\Profile\Models\PhoneType`

**Traits**:

- `InteractsWithUuid` - Uses UUID for primary key

**Constants**:

```php
PhoneType::HOME = 1;   // Home phone
PhoneType::MOBILE = 2; // Mobile/cell phone
PhoneType::OFFICE = 3; // Office phone
PhoneType::OTHER = 4;  // Other type
PhoneType::FAX = 5;    // Fax number
```

**Attributes**:

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | bigint | Primary key |
| `name` | string | Phone type name |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Usage Example**:

```php
use CleaniqueCoders\Profile\Models\PhoneType;

// Use constants
$phone->phone_type_id = PhoneType::MOBILE;

// Query by name
$mobileType = PhoneType::where('name', 'Mobile')->first();
```

### Website

Stores website URLs for any entity.

**Location**: `CleaniqueCoders\Profile\Models\Website`

**Traits**:

- `InteractsWithUuid` - Uses UUID for primary key

**Attributes**:

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | UUID | Primary key |
| `websiteable_type` | string | Polymorphic type |
| `websiteable_id` | bigint | Polymorphic ID |
| `url` | string | Website URL |
| `is_default` | boolean | Whether this is the default website |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Relationships**:

```php
// Get the owning entity
$website->websiteable(); // MorphTo
```

**Usage Example**:

```php
use CleaniqueCoders\Profile\Models\Website;

// Query websites
$websites = Website::where('is_default', true)->get();

// Access owner
$website = Website::find(1);
$owner = $website->websiteable;
```

### Bank

Reference table for bank institutions.

**Location**: `CleaniqueCoders\Profile\Models\Bank`

**Traits**:

- `InteractsWithUuid` - Uses UUID for primary key

**Attributes**:

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | bigint | Primary key |
| `name` | string | Bank name |
| `code` | string | Bank code (optional) |
| `swift_code` | string | SWIFT/BIC code (optional) |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Usage Example**:

```php
use CleaniqueCoders\Profile\Models\Bank;

// Query by name
$bank = Bank::where('name', 'Maybank')->first();

// Query by code
$bank = Bank::where('code', 'MBB')->first();
```

### BankAccount

Stores bank account information for any entity.

**Location**: `CleaniqueCoders\Profile\Models\BankAccount`

**Traits**:

- `InteractsWithUuid` - Uses UUID for primary key

**Attributes**:

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | UUID | Primary key |
| `bankable_type` | string | Polymorphic type |
| `bankable_id` | bigint | Polymorphic ID |
| `bank_id` | bigint | Foreign key to banks table |
| `account_number` | string | Bank account number |
| `account_holder_name` | string | Name on the account |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Relationships**:

```php
// Get the owning entity
$bankAccount->bankable(); // MorphTo

// Get the bank
$bankAccount->bank(); // BelongsTo
```

**Usage Example**:

```php
use CleaniqueCoders\Profile\Models\BankAccount;

// Query with bank details
$accounts = BankAccount::with('bank')->get();

// Access relationships
$account = BankAccount::find(1);
$owner = $account->bankable;
$bank = $account->bank;
```

### Country

Reference table for countries.

**Location**: `CleaniqueCoders\Profile\Models\Country`

**Traits**:

- `InteractsWithUuid` - Uses UUID for primary key

**Attributes**:

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | bigint | Primary key |
| `name` | string | Country name |
| `code` | string | ISO country code (e.g., MY, US) |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Usage Example**:

```php
use CleaniqueCoders\Profile\Models\Country;

// Query by name
$country = Country::where('name', 'Malaysia')->first();

// Query by code
$country = Country::where('code', 'MY')->first();

// Use in address creation
$user->addresses()->create([
    'primary' => '123 Main St',
    'city' => 'Kuala Lumpur',
    'country_id' => Country::where('code', 'MY')->first()->id,
]);
```

## Model Relationships Overview

```
User/Company/Any Model (with traits)
├── addresses() → Address
│   └── country() → Country
├── emails() → Email
├── phones() → Phone
│   └── type() → PhoneType
├── websites() → Website
└── banks() → BankAccount
    └── bank() → Bank
```

## Common Query Patterns

### Eager Loading

```php
// Load all profile relationships
$user = User::with([
    'addresses.country',
    'emails',
    'phones.type',
    'websites',
    'banks.bank'
])->find(1);
```

### Conditional Loading

```php
// Load only if exists
$user = User::with([
    'addresses' => function($query) {
        $query->where('city', 'Kuala Lumpur');
    },
    'phones' => function($query) {
        $query->mobile();
    }
])->find(1);
```

### Existence Queries

```php
// Check if user has addresses
$hasAddresses = $user->addresses()->exists();

// Count phones
$phoneCount = $user->phones()->count();

// Check for default email
$hasDefault = $user->emails()->where('is_default', true)->exists();
```

## What's Next?

- [Database Schema](05-database-schema.md) - Detailed database structure
- [Usage Guides](../03-usage/README.md) - Practical usage examples
- [API Reference](../04-api-reference/README.md) - Complete method documentation
