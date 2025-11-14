# Traits API

Complete reference for all traits provided by the Profile package.

## HasProfile

**Namespace**: `CleaniqueCoders\Profile\Concerns\HasProfile`

A convenience trait that combines all common profile traits.

### Included Traits

- `Addressable`
- `Emailable`
- `Phoneable`
- `Websiteable`

### Usage

```php
use CleaniqueCoders\Profile\Concerns\HasProfile;

class User extends Model
{
    use HasProfile;
}
```

### Methods

Inherits all methods from included traits.

---

## Addressable

**Namespace**: `CleaniqueCoders\Profile\Concerns\Addressable`

Adds address management capability to models.

### Methods

#### `addresses()`

Get all addresses for the model.

**Returns**: `Illuminate\Database\Eloquent\Relations\MorphMany`

**Usage**:

```php
$addresses = $user->addresses;
$address = $user->addresses()->where('city', 'Kuala Lumpur')->first();
```

---

## Emailable

**Namespace**: `CleaniqueCoders\Profile\Concerns\Emailable`

Adds email management capability to models.

### Methods

#### `emails()`

Get all emails for the model.

**Returns**: `Illuminate\Database\Eloquent\Relations\MorphMany`

**Usage**:

```php
$emails = $user->emails;
$defaultEmail = $user->emails()->where('is_default', true)->first();
```

---

## Phoneable

**Namespace**: `CleaniqueCoders\Profile\Concerns\Phoneable`

Adds phone number management capability to models.

### Methods

#### `phones()`

Get all phone numbers for the model.

**Returns**: `Illuminate\Database\Eloquent\Relations\MorphMany`

**Usage**:

```php
$phones = $user->phones;
$mobilePhones = $user->phones()->mobile()->get();
```

---

## Websiteable

**Namespace**: `CleaniqueCoders\Profile\Concerns\Websiteable`

Adds website URL management capability to models.

### Methods

#### `websites()`

Get all websites for the model.

**Returns**: `Illuminate\Database\Eloquent\Relations\MorphMany`

**Usage**:

```php
$websites = $company->websites;
$mainWebsite = $company->websites()->where('is_default', true)->first();
```

---

## Bankable

**Namespace**: `CleaniqueCoders\Profile\Concerns\Bankable`

Adds bank account management capability to models.

### Methods

#### `banks()`

Get all bank accounts for the model.

**Returns**: `Illuminate\Database\Eloquent\Relations\MorphMany`

**Usage**:

```php
$accounts = $employee->banks;
$primaryAccount = $employee->banks()->first();
```

#### `bank()`

Get the associated bank (singular relationship).

**Returns**: `Illuminate\Database\Eloquent\Relations\BelongsTo`

**Usage**:

```php
$bank = $employee->bank;
```

---

## Method Summary

| Trait | Method | Return Type | Description |
|-------|--------|-------------|-------------|
| `Addressable` | `addresses()` | `MorphMany` | Get all addresses |
| `Emailable` | `emails()` | `MorphMany` | Get all emails |
| `Phoneable` | `phones()` | `MorphMany` | Get all phones |
| `Websiteable` | `websites()` | `MorphMany` | Get all websites |
| `Bankable` | `banks()` | `MorphMany` | Get all bank accounts |
| `Bankable` | `bank()` | `BelongsTo` | Get associated bank |

---

## Usage Examples

### Creating Records

```php
// Using trait methods
$user->addresses()->create([...]);
$user->emails()->create([...]);
$user->phones()->create([...]);
$user->websites()->create([...]);
$employee->banks()->create([...]);
```

### Querying Records

```php
// Get all
$user->addresses;
$user->emails;
$user->phones;

// Query with conditions
$user->addresses()->where('city', 'Kuala Lumpur')->get();
$user->emails()->where('is_default', true)->first();
$user->phones()->mobile()->get();
```

### Counting Records

```php
$addressCount = $user->addresses()->count();
$emailCount = $user->emails()->count();
$phoneCount = $user->phones()->count();
```

### Checking Existence

```php
$hasAddresses = $user->addresses()->exists();
$hasEmails = $user->emails()->exists();
$hasPhones = $user->phones()->exists();
```
