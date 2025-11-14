# Models API

Complete reference for all models provided by the Profile package.

## Address

**Namespace**: `CleaniqueCoders\Profile\Models\Address`

**Table**: `addresses`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | int | Primary key |
| `uuid` | string | Unique identifier |
| `addressable_type` | string | Polymorphic type |
| `addressable_id` | int | Polymorphic ID |
| `country_id` | int\|null | Foreign key to countries |
| `primary` | string\|null | Primary address line |
| `secondary` | string\|null | Secondary address line |
| `city` | string\|null | City name |
| `postcode` | string\|null | Postal/ZIP code |
| `state` | string\|null | State/province |
| `is_default` | bool | Default address flag |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |
| `deleted_at` | timestamp\|null | Soft delete time |

### Relationships

```php
// Get owning entity
$address->addressable(); // MorphTo

// Get country
$address->country(); // BelongsTo
```

### Usage

```php
use CleaniqueCoders\Profile\Models\Address;

$address = Address::find(1);
$owner = $address->addressable;
$country = $address->country;
```

---

## Email

**Namespace**: `CleaniqueCoders\Profile\Models\Email`

**Table**: `emails`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | int | Primary key |
| `uuid` | string | Unique identifier |
| `emailable_type` | string | Polymorphic type |
| `emailable_id` | int | Polymorphic ID |
| `email` | string | Email address |
| `is_default` | bool | Default email flag |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |
| `deleted_at` | timestamp\|null | Soft delete time |

### Relationships

```php
// Get owning entity
$email->emailable(); // MorphTo
```

### Usage

```php
use CleaniqueCoders\Profile\Models\Email;

$email = Email::where('email', 'john@example.com')->first();
$owner = $email->emailable;
```

---

## Phone

**Namespace**: `CleaniqueCoders\Profile\Models\Phone`

**Table**: `phones`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | int | Primary key |
| `uuid` | string | Unique identifier |
| `phoneable_type` | string | Polymorphic type |
| `phoneable_id` | int | Polymorphic ID |
| `phone_type_id` | int | Foreign key to phone_types |
| `phone_number` | string\|null | Phone number |
| `is_default` | bool | Default phone flag |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |
| `deleted_at` | timestamp\|null | Soft delete time |

### Relationships

```php
// Get owning entity
$phone->phoneable(); // MorphTo

// Get phone type
$phone->type(); // BelongsTo
```

### Scopes

```php
// Available query scopes
Phone::home()->get();
Phone::mobile()->get();
Phone::office()->get();
Phone::other()->get();
Phone::fax()->get();
```

### Usage

```php
use CleaniqueCoders\Profile\Models\Phone;

$phone = Phone::mobile()->first();
$owner = $phone->phoneable;
$type = $phone->type;
```

---

## PhoneType

**Namespace**: `CleaniqueCoders\Profile\Models\PhoneType`

**Table**: `phone_types`

### Constants

```php
PhoneType::HOME = 1;
PhoneType::MOBILE = 2;
PhoneType::OFFICE = 3;
PhoneType::OTHER = 4;
PhoneType::FAX = 5;
```

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | int | Primary key |
| `name` | string | Phone type name |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |

### Usage

```php
use CleaniqueCoders\Profile\Models\PhoneType;

$phone->phone_type_id = PhoneType::MOBILE;
```

---

## Website

**Namespace**: `CleaniqueCoders\Profile\Models\Website`

**Table**: `websites`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | int | Primary key |
| `uuid` | string | Unique identifier |
| `websiteable_type` | string | Polymorphic type |
| `websiteable_id` | int | Polymorphic ID |
| `url` | string | Website URL |
| `is_default` | bool | Default website flag |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |
| `deleted_at` | timestamp\|null | Soft delete time |

### Relationships

```php
// Get owning entity
$website->websiteable(); // MorphTo
```

### Usage

```php
use CleaniqueCoders\Profile\Models\Website;

$website = Website::where('url', 'https://example.com')->first();
$owner = $website->websiteable;
```

---

## Bank

**Namespace**: `CleaniqueCoders\Profile\Models\Bank`

**Table**: `banks`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | int | Primary key |
| `name` | string | Bank name |
| `code` | string\|null | Bank code |
| `swift_code` | string\|null | SWIFT/BIC code |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |

### Usage

```php
use CleaniqueCoders\Profile\Models\Bank;

$bank = Bank::where('name', 'Maybank')->first();
$bank = Bank::where('code', 'MBB')->first();
```

---

## BankAccount

**Namespace**: `CleaniqueCoders\Profile\Models\BankAccount`

**Table**: `bank_accounts`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | int | Primary key |
| `uuid` | string | Unique identifier |
| `bankable_type` | string | Polymorphic type |
| `bankable_id` | int | Polymorphic ID |
| `bank_id` | int | Foreign key to banks |
| `account_number` | string | Account number |
| `account_holder_name` | string | Account holder name |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |
| `deleted_at` | timestamp\|null | Soft delete time |

### Relationships

```php
// Get owning entity
$account->bankable(); // MorphTo

// Get bank
$account->bank(); // BelongsTo
```

### Usage

```php
use CleaniqueCoders\Profile\Models\BankAccount;

$account = BankAccount::with('bank')->first();
$owner = $account->bankable;
$bank = $account->bank;
```

---

## Country

**Namespace**: `CleaniqueCoders\Profile\Models\Country`

**Table**: `countries`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | int | Primary key |
| `name` | string | Country name |
| `code` | string\|null | ISO country code |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update time |

### Usage

```php
use CleaniqueCoders\Profile\Models\Country;

$country = Country::where('code', 'MY')->first();
$country = Country::where('name', 'Malaysia')->first();
```

---

## Model Summary

| Model | Table | Has UUID | Soft Delete | Polymorphic |
|-------|-------|----------|-------------|-------------|
| Address | addresses | ✅ | ✅ | ✅ |
| Email | emails | ✅ | ✅ | ✅ |
| Phone | phones | ✅ | ✅ | ✅ |
| PhoneType | phone_types | ❌ | ❌ | ❌ |
| Website | websites | ✅ | ✅ | ✅ |
| Bank | banks | ❌ | ❌ | ❌ |
| BankAccount | bank_accounts | ✅ | ✅ | ✅ |
| Country | countries | ❌ | ❌ | ❌ |
