# Database Schema

This document describes the database schema used by the Profile package.

## Overview

The Profile package creates 8 tables:

1. `addresses` - Store physical addresses
2. `emails` - Store email addresses
3. `phones` - Store phone numbers
4. `websites` - Store website URLs
5. `bank_accounts` - Store bank account details
6. `banks` - Reference table for banks
7. `countries` - Reference table for countries
8. `phone_types` - Reference table for phone types

## Tables

### addresses

Stores physical address information with polymorphic relationships.

```sql
CREATE TABLE addresses (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    country_id BIGINT UNSIGNED NULLABLE,
    addressable_id INT UNSIGNED,
    addressable_type VARCHAR(255),
    primary TEXT NULLABLE,
    secondary TEXT NULLABLE,
    postcode VARCHAR(255) NULLABLE,
    city VARCHAR(255) NULLABLE,
    state VARCHAR(255) NULLABLE,
    is_default BOOLEAN DEFAULT false,
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_addressable (addressable_type, addressable_id),
    FOREIGN KEY (country_id) REFERENCES countries(id)
);
```

**Key Columns**:

- `uuid`: Unique identifier for the address
- `addressable_type`: Fully qualified class name of the owning model
- `addressable_id`: ID of the owning model
- `country_id`: Foreign key to countries table
- `is_default`: Flag to mark default address
- `deleted_at`: Soft delete timestamp

### emails

Stores email addresses with polymorphic relationships.

```sql
CREATE TABLE emails (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    emailable_id INT UNSIGNED,
    emailable_type VARCHAR(255),
    email VARCHAR(255),
    is_default BOOLEAN DEFAULT false,
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_emailable (emailable_type, emailable_id),
    INDEX idx_email (email)
);
```

**Key Columns**:

- `uuid`: Unique identifier for the email
- `emailable_type`: Fully qualified class name of the owning model
- `emailable_id`: ID of the owning model
- `email`: Email address
- `is_default`: Flag to mark default email

### phones

Stores phone numbers with type classification and polymorphic relationships.

```sql
CREATE TABLE phones (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    phone_type_id BIGINT UNSIGNED DEFAULT 1,
    phoneable_id INT UNSIGNED,
    phoneable_type VARCHAR(255),
    phone_number VARCHAR(255) NULLABLE,
    is_default BOOLEAN DEFAULT false,
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_phoneable (phoneable_type, phoneable_id),
    INDEX idx_phone_type (phone_type_id),
    FOREIGN KEY (phone_type_id) REFERENCES phone_types(id)
);
```

**Key Columns**:

- `uuid`: Unique identifier for the phone
- `phoneable_type`: Fully qualified class name of the owning model
- `phoneable_id`: ID of the owning model
- `phone_type_id`: Foreign key to phone_types table (defaults to HOME)
- `phone_number`: Phone number in any format
- `is_default`: Flag to mark default phone

### phone_types

Reference table for phone number types.

```sql
CREATE TABLE phone_types (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Default Data**:

| ID | Name |
|----|------|
| 1 | Home |
| 2 | Mobile |
| 3 | Office |
| 4 | Other |
| 5 | Fax |

### websites

Stores website URLs with polymorphic relationships.

```sql
CREATE TABLE websites (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    websiteable_id INT UNSIGNED,
    websiteable_type VARCHAR(255),
    url VARCHAR(255),
    is_default BOOLEAN DEFAULT false,
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_websiteable (websiteable_type, websiteable_id)
);
```

**Key Columns**:

- `uuid`: Unique identifier for the website
- `websiteable_type`: Fully qualified class name of the owning model
- `websiteable_id`: ID of the owning model
- `url`: Website URL
- `is_default`: Flag to mark default website

### bank_accounts

Stores bank account information with polymorphic relationships.

```sql
CREATE TABLE bank_accounts (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    bank_id BIGINT UNSIGNED,
    bankable_id INT UNSIGNED,
    bankable_type VARCHAR(255),
    account_number VARCHAR(255),
    account_holder_name VARCHAR(255),
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_bankable (bankable_type, bankable_id),
    FOREIGN KEY (bank_id) REFERENCES banks(id)
);
```

**Key Columns**:

- `uuid`: Unique identifier for the bank account
- `bankable_type`: Fully qualified class name of the owning model
- `bankable_id`: ID of the owning model
- `bank_id`: Foreign key to banks table
- `account_number`: Bank account number
- `account_holder_name`: Name on the account

### banks

Reference table for bank institutions.

```sql
CREATE TABLE banks (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    code VARCHAR(255) NULLABLE,
    swift_code VARCHAR(255) NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_code (code)
);
```

**Key Columns**:

- `name`: Bank name
- `code`: Bank code (e.g., MBB for Maybank)
- `swift_code`: SWIFT/BIC code for international transfers

### countries

Reference table for countries.

```sql
CREATE TABLE countries (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    code VARCHAR(2) NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_code (code)
);
```

**Key Columns**:

- `name`: Country name
- `code`: ISO 3166-1 alpha-2 country code (e.g., MY, US, GB)

## Polymorphic Relationships

All profile tables use polymorphic relationships with the pattern `{type}_type` and `{type}_id`:

| Table | Type Column | ID Column | Example Values |
|-------|-------------|-----------|----------------|
| addresses | `addressable_type` | `addressable_id` | `App\Models\User`, `1` |
| emails | `emailable_type` | `emailable_id` | `App\Models\Company`, `5` |
| phones | `phoneable_type` | `phoneable_id` | `App\Models\Employee`, `10` |
| websites | `websiteable_type` | `websiteable_id` | `App\Models\Organization`, `3` |
| bank_accounts | `bankable_type` | `bankable_id` | `App\Models\Employee`, `7` |

## Indexes

### Performance Indexes

The package creates indexes on frequently queried columns:

**Polymorphic Indexes**:

```sql
-- For efficient polymorphic queries
INDEX idx_addressable (addressable_type, addressable_id)
INDEX idx_emailable (emailable_type, emailable_id)
INDEX idx_phoneable (phoneable_type, phoneable_id)
INDEX idx_websiteable (websiteable_type, websiteable_id)
INDEX idx_bankable (bankable_type, bankable_id)
```

**Foreign Key Indexes**:

```sql
-- For efficient joins
INDEX idx_phone_type (phone_type_id)
INDEX idx_code (code) -- on banks and countries
INDEX idx_email (email) -- on emails
```

## Soft Deletes

The following tables support soft deletes:

- `addresses`
- `emails`
- `phones`
- `websites`
- `bank_accounts`

Soft deleted records have a non-null `deleted_at` timestamp and are excluded from default queries.

**Usage**:

```php
// Soft delete
$address->delete();

// Include soft deleted
$addresses = $user->addresses()->withTrashed()->get();

// Only soft deleted
$deletedAddresses = $user->addresses()->onlyTrashed()->get();

// Restore
$address->restore();

// Permanently delete
$address->forceDelete();
```

## UUID Support

All profile tables include a `uuid` column for unique identification across systems:

```php
// Access UUID
$address = Address::find(1);
echo $address->uuid; // e.g., "550e8400-e29b-41d4-a716-446655440000"

// Query by UUID
$address = Address::where('uuid', $uuid)->first();
```

This is useful for:

- External API integrations
- Distributed systems
- Avoiding ID collisions in multi-tenant applications

## Data Integrity

### Foreign Key Constraints

The package enforces data integrity through foreign key constraints:

```sql
-- Address → Country
FOREIGN KEY (country_id) REFERENCES countries(id)

-- Phone → PhoneType
FOREIGN KEY (phone_type_id) REFERENCES phone_types(id)

-- BankAccount → Bank
FOREIGN KEY (bank_id) REFERENCES banks(id)
```

### Default Values

- `is_default`: Defaults to `false` for all profile types
- `phone_type_id`: Defaults to `1` (Home) if not specified

## Migration Order

Migrations should be run in this order to respect foreign key constraints:

1. `create_countries_table` - Reference table
2. `create_phone_types_table` - Reference table
3. `create_banks_table` - Reference table
4. `create_addresses_table` - References countries
5. `create_phones_table` - References phone_types
6. `create_emails_table` - No dependencies
7. `create_websites_table` - No dependencies
8. `create_bank_accounts_table` - References banks (if exists)

The package handles this automatically when you run:

```bash
php artisan migrate
```

## Schema Customization

### Adding Custom Columns

You can add custom columns through your own migrations:

```php
Schema::table('addresses', function (Blueprint $table) {
    $table->string('label')->nullable(); // e.g., "Home", "Work"
    $table->boolean('is_billing')->default(false);
});
```

### Extending Models

Create your own models that extend the package models:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Models\Address as BaseAddress;

class Address extends BaseAddress
{
    protected $appends = ['formatted_address'];

    public function getFormattedAddressAttribute()
    {
        return "{$this->primary}, {$this->city} {$this->postcode}";
    }
}
```

Then configure the package to use your model:

```php
// config/profile.php
'providers' => [
    'address' => [
        'model' => \App\Models\Address::class,
        'type' => 'addressable',
    ],
],
```

## What's Next?

- [Usage Guides](../03-usage/README.md) - Learn how to use the models
- [API Reference](../04-api-reference/README.md) - Complete API documentation
