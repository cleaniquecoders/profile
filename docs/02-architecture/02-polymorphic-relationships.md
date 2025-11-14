# Polymorphic Relationships

Understanding polymorphic relationships is key to using the Profile package effectively.

## What are Polymorphic Relationships?

Polymorphic relationships allow a model to belong to more than one type of model on a single association. In the Profile package, this means a single `addresses` table can serve `User`, `Company`, `Employee`, or any other model.

## How It Works

### Traditional Approach (Without Polymorphism)

Without polymorphic relationships, you'd need separate tables:

```sql
-- user_addresses table
CREATE TABLE user_addresses (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    address TEXT,
    ...
);

-- company_addresses table
CREATE TABLE company_addresses (
    id BIGINT PRIMARY KEY,
    company_id BIGINT,
    address TEXT,
    ...
);
```

### Polymorphic Approach (With This Package)

With polymorphic relationships, you have one table:

```sql
-- addresses table
CREATE TABLE addresses (
    id BIGINT PRIMARY KEY,
    addressable_type VARCHAR(255),  -- 'App\Models\User' or 'App\Models\Company'
    addressable_id BIGINT,           -- The ID of the User or Company
    primary VARCHAR(255),
    secondary VARCHAR(255),
    city VARCHAR(255),
    ...
);
```

The `addressable_type` and `addressable_id` columns together form the polymorphic relationship:

- `addressable_type`: Stores the fully qualified class name (e.g., `App\Models\User`)
- `addressable_id`: Stores the ID of that model

## Example in Practice

### Database Records

```
addresses table:
+----+-------------------+----------------+------------------+
| id | addressable_type  | addressable_id | primary          |
+----+-------------------+----------------+------------------+
| 1  | App\Models\User   | 1              | 123 Main St      |
| 2  | App\Models\User   | 1              | 456 Work Ave     |
| 3  | App\Models\Company| 5              | 789 Business Blvd|
| 4  | App\Models\Company| 5              | 321 Branch St    |
+----+-------------------+----------------+------------------+
```

### Laravel Code

```php
// User with ID 1 has two addresses (rows 1 and 2)
$user = User::find(1);
$addresses = $user->addresses; // Returns 2 addresses

// Company with ID 5 has two addresses (rows 3 and 4)
$company = Company::find(5);
$addresses = $company->addresses; // Returns 2 addresses
```

## Implementation Details

### Trait Definition

The `Addressable` trait defines the polymorphic relationship:

```php
namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Addressable
{
    public function addresses(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.address.model'),  // Address::class
            config('profile.providers.address.type')     // 'addressable'
        );
    }
}
```

### Model Definition

The `Address` model defines the inverse relationship:

```php
namespace CleaniqueCoders\Profile\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
```

## Configuration

The polymorphic type names are configured in `config/profile.php`:

```php
'providers' => [
    'address' => [
        'model' => \CleaniqueCoders\Profile\Models\Address::class,
        'type' => 'addressable',  // This is the morph name
    ],
],
```

The `type` value (`addressable`) determines the column prefix in the database:

- `addressable_type`
- `addressable_id`

## All Polymorphic Relations in the Package

| Trait | Morph Name | Table | Type Column | ID Column |
|-------|-----------|-------|-------------|-----------|
| `Addressable` | `addressable` | `addresses` | `addressable_type` | `addressable_id` |
| `Emailable` | `emailable` | `emails` | `emailable_type` | `emailable_id` |
| `Phoneable` | `phoneable` | `phones` | `phoneable_type` | `phoneable_id` |
| `Websiteable` | `websiteable` | `websites` | `websiteable_type` | `websiteable_id` |
| `Bankable` | `bankable` | `bank_accounts` | `bankable_type` | `bankable_id` |

## Advantages

### 1. Database Efficiency

```php
// Instead of 5 separate address tables for different entities:
// user_addresses, company_addresses, employee_addresses, etc.

// You have ONE table:
addresses
```

### 2. Code Reusability

```php
// The same trait works for any model
class User extends Model { use Addressable; }
class Company extends Model { use Addressable; }
class Vendor extends Model { use Addressable; }
```

### 3. Consistent Logic

```php
// Same methods, same behavior across all models
$user->addresses()->create([...]);
$company->addresses()->create([...]);
$vendor->addresses()->create([...]);
```

### 4. Flexible Queries

```php
// Query all addresses across all entity types
Address::where('city', 'Kuala Lumpur')->get();

// Query specific entity's addresses
$user->addresses()->where('city', 'Kuala Lumpur')->get();

// Get the parent entity from an address
$address = Address::find(1);
$owner = $address->addressable; // Could be User, Company, etc.
```

## Common Patterns

### Creating Records

```php
// Using the relationship
$user->addresses()->create([
    'primary' => '123 Main St',
    'city' => 'Petaling Jaya',
]);

// Direct creation with morph fields
Address::create([
    'addressable_type' => User::class,
    'addressable_id' => $user->id,
    'primary' => '123 Main St',
    'city' => 'Petaling Jaya',
]);
```

### Querying Records

```php
// Get all addresses for a model
$user->addresses;

// Filter addresses
$user->addresses()->where('city', 'Kuala Lumpur')->get();

// Count addresses
$user->addresses()->count();

// Check if has addresses
$user->addresses()->exists();
```

### Accessing Parent

```php
// From an address, get the owner
$address = Address::find(1);
$owner = $address->addressable;

// Check owner type
if ($address->addressable instanceof User) {
    // It's a user's address
}
```

## What's Next?

- [Traits](03-traits.md) - Detailed documentation on each trait
- [Models](04-models.md) - Understanding the model structure
- [Database Schema](05-database-schema.md) - Database design details
