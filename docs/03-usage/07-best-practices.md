# Best Practices

Tips and recommendations for using the Profile package effectively.

## Trait Selection

### Use Only What You Need

```php
// ❌ Bad: Using HasProfile when you only need addresses
class Store extends Model
{
    use HasProfile; // Includes Addressable, Emailable, Phoneable, Websiteable
}

// ✅ Good: Use specific traits
class Store extends Model
{
    use Addressable, Phoneable;
}
```

### Combine Traits Appropriately

```php
// For customer profiles
class Customer extends Model
{
    use HasProfile; // Addresses, Emails, Phones, Websites
}

// For employees
class Employee extends Model
{
    use HasProfile, Bankable; // Add banking information
}

// For companies
class Company extends Model
{
    use Addressable, Emailable, Phoneable, Websiteable;
}
```

## Data Management

### Always Set Default Flags

```php
// ✅ Good: Explicit default management
if ($isDefault) {
    $user->emails()->update(['is_default' => false]);
}

$user->emails()->create([
    'email' => 'john@example.com',
    'is_default' => $isDefault,
]);
```

### Validate Before Creating

```php
// ✅ Good: Validate input
$validated = $request->validate([
    'email' => 'required|email|max:255',
    'is_default' => 'boolean',
]);

$user->emails()->create($validated);
```

### Handle Duplicates

```php
// ✅ Good: Check for duplicates
public function addEmail(string $email, bool $isDefault = false)
{
    if ($this->hasEmail($email)) {
        return;
    }

    if ($isDefault) {
        $this->emails()->update(['is_default' => false]);
    }

    $this->emails()->create([
        'email' => $email,
        'is_default' => $isDefault,
    ]);
}
```

## Performance

### Eager Load Relationships

```php
// ❌ Bad: N+1 query problem
$users = User::all();
foreach ($users as $user) {
    echo $user->addresses->first()->city; // Queries for each user
}

// ✅ Good: Eager loading
$users = User::with('addresses')->get();
foreach ($users as $user) {
    echo $user->addresses->first()->city; // Single query
}
```

### Use Specific Queries

```php
// ❌ Bad: Loading unnecessary data
$user = User::with([
    'addresses',
    'emails',
    'phones',
    'websites',
    'banks'
])->find(1);

// ✅ Good: Load only what you need
$user = User::with(['addresses.country'])->find(1);
```

### Cache Frequently Accessed Data

```php
// ✅ Good: Cache user profile
public function getCachedProfile()
{
    return cache()->remember("user.{$this->id}.profile", 3600, function() {
        return $this->load([
            'addresses.country',
            'emails',
            'phones.type',
        ]);
    });
}
```

## Security

### Protect Sensitive Data

```php
// ✅ Good: Hide sensitive fields
class BankAccount extends Model
{
    protected $hidden = [
        'account_number',
    ];

    // Provide masked version
    public function getMaskedAccountNumberAttribute()
    {
        return '****' . substr($this->account_number, -4);
    }
}
```

### Validate Permissions

```php
// ✅ Good: Check permissions
public function updateAddress(Request $request, User $user, Address $address)
{
    // Ensure address belongs to user
    if ($address->addressable_id !== $user->id) {
        abort(403);
    }

    $address->update($request->validated());
}
```

### Sanitize Input

```php
// ✅ Good: Clean phone numbers
public function setPhoneNumberAttribute($value)
{
    // Remove all non-numeric characters except +
    $this->attributes['phone_number'] = preg_replace('/[^0-9+]/', '', $value);
}
```

## Data Integrity

### Use Transactions

```php
// ✅ Good: Use transactions for multiple operations
DB::transaction(function() use ($user, $data) {
    // Remove all existing addresses
    $user->addresses()->delete();

    // Create new addresses
    foreach ($data['addresses'] as $address) {
        $user->addresses()->create($address);
    }
});
```

### Soft Deletes

```php
// ✅ Good: Use soft deletes for audit trail
$address->delete(); // Soft delete

// Later, restore if needed
$address->restore();

// Only force delete when absolutely necessary
$address->forceDelete();
```

### Maintain Referential Integrity

```php
// ✅ Good: Check foreign keys exist
$validated = $request->validate([
    'country_id' => 'required|exists:countries,id',
    'bank_id' => 'required|exists:banks,id',
    'phone_type_id' => 'required|exists:phone_types,id',
]);
```

## Code Organization

### Use Helper Methods

```php
// ✅ Good: Encapsulate logic in methods
class User extends Model
{
    use HasProfile;

    public function getPrimaryAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    public function getFormattedProfile(): array
    {
        return [
            'address' => $this->getPrimaryAddress()?->primary,
            'email' => $this->getDefaultEmailAddress(),
            'phone' => $this->getDefaultPhoneNumber(),
        ];
    }
}
```

### Create Custom Scopes

```php
// ✅ Good: Reusable query logic
class User extends Model
{
    public function scopeWithCompleteProfile($query)
    {
        return $query->whereHas('addresses')
                     ->whereHas('emails')
                     ->whereHas('phones');
    }
}

// Usage
$users = User::withCompleteProfile()->get();
```

### Extract to Service Classes

```php
// ✅ Good: Service class for complex operations
class ProfileService
{
    public function createCompleteProfile(User $user, array $data): void
    {
        DB::transaction(function() use ($user, $data) {
            if (isset($data['address'])) {
                $user->addresses()->create($data['address']);
            }

            if (isset($data['email'])) {
                $user->emails()->create($data['email']);
            }

            if (isset($data['phone'])) {
                $user->phones()->create($data['phone']);
            }
        });
    }
}
```

## Testing

### Test Profile Creation

```php
test('user can have multiple addresses', function() {
    $user = User::factory()->create();

    $user->addresses()->create([
        'primary' => '123 Main St',
        'city' => 'Kuala Lumpur',
    ]);

    $user->addresses()->create([
        'primary' => '456 Work Ave',
        'city' => 'Petaling Jaya',
    ]);

    expect($user->addresses)->toHaveCount(2);
});
```

### Test Relationships

```php
test('address belongs to user', function() {
    $user = User::factory()->create();

    $address = $user->addresses()->create([
        'primary' => '123 Main St',
        'city' => 'Kuala Lumpur',
    ]);

    expect($address->addressable)->toBeInstanceOf(User::class);
    expect($address->addressable->id)->toBe($user->id);
});
```

### Test Default Management

```php
test('only one email can be default', function() {
    $user = User::factory()->create();

    $email1 = $user->emails()->create([
        'email' => 'first@example.com',
        'is_default' => true,
    ]);

    $email2 = $user->emails()->create([
        'email' => 'second@example.com',
        'is_default' => true,
    ]);

    // First email should no longer be default
    expect($email1->fresh()->is_default)->toBeFalse();
    expect($email2->fresh()->is_default)->toBeTrue();
});
```

## Documentation

### Document Custom Extensions

```php
/**
 * Get the user's primary contact information
 *
 * @return array{address: string|null, email: string|null, phone: string|null}
 */
public function getPrimaryContact(): array
{
    return [
        'address' => $this->getPrimaryAddress()?->primary,
        'email' => $this->getDefaultEmailAddress(),
        'phone' => $this->getDefaultPhoneNumber(),
    ];
}
```

### Add PHPDoc Type Hints

```php
/**
 * @return \Illuminate\Database\Eloquent\Collection<Address>
 */
public function getActiveAddresses()
{
    return $this->addresses()
        ->where('is_active', true)
        ->get();
}
```

## Migration

### Version Control Schema Changes

```php
// Always create migrations for schema changes
Schema::table('addresses', function (Blueprint $table) {
    $table->boolean('is_verified')->default(false);
    $table->timestamp('verified_at')->nullable();
});
```

### Provide Rollback

```php
public function down()
{
    Schema::table('addresses', function (Blueprint $table) {
        $table->dropColumn(['is_verified', 'verified_at']);
    });
}
```

## Summary

- ✅ Use specific traits, not always `HasProfile`
- ✅ Eager load relationships to avoid N+1 queries
- ✅ Always validate input data
- ✅ Use transactions for multiple operations
- ✅ Soft delete for audit trails
- ✅ Cache frequently accessed data
- ✅ Protect sensitive information
- ✅ Write comprehensive tests
- ✅ Document custom extensions
- ✅ Use helper methods for common operations

## What's Next?

- [API Reference](../04-api-reference/README.md) - Complete method documentation
