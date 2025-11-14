# Overview

The Profile package is designed to provide a flexible, reusable solution for managing common profile information across different entities in your Laravel application.

## Problem Statement

Consider these common scenarios:

- A **Company** has addresses, phone numbers, emails, and websites
- An **Employee** has addresses, phone numbers, emails, and bank accounts
- A **Customer** has addresses, phone numbers, and emails
- A **Vendor** has addresses, phone numbers, emails, and websites

Without a proper abstraction, you would need to create separate tables for each entity:

- `company_addresses`, `employee_addresses`, `customer_addresses`, `vendor_addresses`
- `company_phones`, `employee_phones`, `customer_phones`, `vendor_phones`
- And so on...

This leads to:

- **Code duplication**: Similar logic repeated across multiple models
- **Database bloat**: Many similar tables with identical structures
- **Maintenance overhead**: Changes need to be applied to multiple places

## Solution

The Profile package solves this problem using **Laravel's Polymorphic Relationships**. Instead of creating separate tables for each entity, we have:

- One `addresses` table for all entities
- One `phones` table for all entities
- One `emails` table for all entities
- One `websites` table for all entities
- One `bank_accounts` table for all entities

## Key Design Principles

### 1. Polymorphic Relationships

The package uses Laravel's polymorphic relationships to allow any model to have profile information without duplicating tables or code.

```php
// User model
class User extends Authenticatable
{
    use HasProfile;
}

// Company model
class Company extends Model
{
    use HasProfile;
}

// Both can use the same relationships
$user->addresses()->create([...]);
$company->addresses()->create([...]);
```

### 2. Trait-Based Composition

Functionality is provided through traits, allowing you to pick only what you need:

```php
// Full profile
use HasProfile; // Includes Addressable, Emailable, Phoneable, Websiteable

// Or individual traits
use Addressable;
use Phoneable;
use Bankable;
```

### 3. Configuration-Driven

Models and behavior can be customized through configuration without modifying package code:

```php
'providers' => [
    'address' => [
        'model' => \App\Models\CustomAddress::class, // Use your own model
        'type' => 'addressable',
    ],
],
```

### 4. Standardized Data

Reference data (countries, phone types, banks) is seeded automatically for consistency across your application.

## Package Structure

```
src/
├── Concerns/           # Traits for polymorphic relationships
│   ├── Addressable.php
│   ├── Emailable.php
│   ├── Phoneable.php
│   ├── Websiteable.php
│   ├── Bankable.php
│   └── HasProfile.php
├── Models/            # Eloquent models
│   ├── Address.php
│   ├── Email.php
│   ├── Phone.php
│   ├── PhoneType.php
│   ├── Website.php
│   ├── Bank.php
│   ├── BankAccount.php
│   └── Country.php
└── ProfileServiceProvider.php
```

## Benefits

### For Developers

- **Less code**: Write profile logic once, use everywhere
- **Type safety**: Proper IDE autocomplete and type hints
- **Testable**: Easy to test with traits and separated concerns
- **Extensible**: Add custom behavior through model extension

### For Applications

- **Consistent data structure**: All entities use the same profile schema
- **Database efficiency**: No duplicate tables or redundant data
- **Easy queries**: Query all addresses, phones, emails across all entities
- **Scalability**: Add new entities without changing database schema

### For Maintenance

- **Single source of truth**: Changes in one place affect all entities
- **Migration friendly**: Add fields once, available everywhere
- **Clear patterns**: Easy for new developers to understand and extend

## What's Next?

- [Polymorphic Relationships](02-polymorphic-relationships.md) - Deep dive into polymorphic design
- [Traits](03-traits.md) - Understand each trait in detail
- [Models](04-models.md) - Explore the model structure
