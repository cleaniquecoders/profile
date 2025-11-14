# Working with Addresses

Learn how to manage address information using the `Addressable` trait.

## Setup

Add the `Addressable` trait to your model:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\Addressable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Addressable;
}
```

Or use the `HasProfile` trait for all profile features:

```php
use CleaniqueCoders\Profile\Concerns\HasProfile;

class User extends Model
{
    use HasProfile;
}
```

## Creating Addresses

### Basic Address Creation

```php
use App\Models\User;
use CleaniqueCoders\Profile\Models\Country;

$user = User::find(1);

$address = $user->addresses()->create([
    'primary' => '123 Main Street',
    'secondary' => 'Apartment 4B',
    'city' => 'Kuala Lumpur',
    'postcode' => '50088',
    'state' => 'Federal Territory',
    'country_id' => Country::where('code', 'MY')->first()->id,
    'is_default' => true,
]);
```

### Creating Multiple Addresses

```php
// Home address
$user->addresses()->create([
    'primary' => '456 Home Ave',
    'city' => 'Petaling Jaya',
    'postcode' => '46150',
    'state' => 'Selangor',
    'country_id' => Country::where('name', 'Malaysia')->first()->id,
    'is_default' => true,
]);

// Work address
$user->addresses()->create([
    'primary' => '789 Corporate Tower',
    'secondary' => 'Floor 15, Suite 1500',
    'city' => 'Kuala Lumpur',
    'postcode' => '50088',
    'state' => 'Federal Territory',
    'country_id' => Country::where('name', 'Malaysia')->first()->id,
    'is_default' => false,
]);
```

### Minimal Address

Only `primary` is required, other fields are optional:

```php
$user->addresses()->create([
    'primary' => 'P.O. Box 12345',
]);
```

## Retrieving Addresses

### Get All Addresses

```php
$addresses = $user->addresses;

foreach ($addresses as $address) {
    echo $address->primary;
    echo $address->city;
}
```

### Get with Country Information

```php
$addresses = $user->addresses()->with('country')->get();

foreach ($addresses as $address) {
    echo $address->primary;
    echo $address->country->name;
}
```

### Get Default Address

```php
$defaultAddress = $user->addresses()->where('is_default', true)->first();
```

### Filter by Location

```php
// Get addresses in specific city
$klAddresses = $user->addresses()->where('city', 'Kuala Lumpur')->get();

// Get addresses in specific state
$selangorAddresses = $user->addresses()->where('state', 'Selangor')->get();

// Get addresses by postcode
$postcodeAddresses = $user->addresses()
    ->where('postcode', '50088')
    ->get();
```

### Filter by Country

```php
// Get all Malaysian addresses
$malaysianAddresses = $user->addresses()
    ->whereHas('country', function($query) {
        $query->where('code', 'MY');
    })
    ->get();

// Or with country loaded
$malaysianAddresses = $user->addresses()
    ->with('country')
    ->whereHas('country', function($query) {
        $query->where('name', 'Malaysia');
    })
    ->get();
```

## Updating Addresses

### Update Existing Address

```php
$address = $user->addresses()->first();

$address->update([
    'primary' => '999 New Street',
    'city' => 'Shah Alam',
    'postcode' => '40000',
]);
```

### Set as Default

```php
// Remove default flag from all addresses
$user->addresses()->update(['is_default' => false]);

// Set specific address as default
$address = $user->addresses()->find($addressId);
$address->update(['is_default' => true]);
```

### Update with Country Change

```php
$address->update([
    'city' => 'Singapore',
    'postcode' => '018956',
    'country_id' => Country::where('code', 'SG')->first()->id,
]);
```

## Deleting Addresses

### Soft Delete (Recommended)

```php
$address = $user->addresses()->first();
$address->delete(); // Soft delete
```

### Restore Soft Deleted

```php
$address = $user->addresses()->withTrashed()->find($addressId);
$address->restore();
```

### Permanent Delete

```php
$address->forceDelete(); // Permanently remove from database
```

### Delete All Addresses

```php
$user->addresses()->delete(); // Soft delete all
```

## Advanced Patterns

### Creating Helper Methods

```php
class User extends Model
{
    use HasProfile;

    /**
     * Get the primary/default address
     */
    public function getPrimaryAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    /**
     * Get home address
     */
    public function getHomeAddress()
    {
        return $this->addresses()
            ->where('primary', 'LIKE', '%Home%')
            ->orWhere('secondary', 'LIKE', '%Home%')
            ->first();
    }

    /**
     * Get formatted address string
     */
    public function getFormattedAddress()
    {
        $address = $this->getPrimaryAddress();

        if (!$address) {
            return null;
        }

        return collect([
            $address->primary,
            $address->secondary,
            $address->city,
            $address->postcode,
            $address->state,
            $address->country->name ?? null,
        ])->filter()->implode(', ');
    }
}
```

### Validation

```php
use Illuminate\Http\Request;
use CleaniqueCoders\Profile\Models\Country;

public function storeAddress(Request $request, User $user)
{
    $validated = $request->validate([
        'primary' => 'required|string|max:255',
        'secondary' => 'nullable|string|max:255',
        'city' => 'required|string|max:100',
        'postcode' => 'required|string|max:20',
        'state' => 'required|string|max:100',
        'country_id' => 'required|exists:countries,id',
        'is_default' => 'boolean',
    ]);

    // If setting as default, remove default from others
    if ($validated['is_default'] ?? false) {
        $user->addresses()->update(['is_default' => false]);
    }

    return $user->addresses()->create($validated);
}
```

### Geocoding Integration

```php
class Address extends \CleaniqueCoders\Profile\Models\Address
{
    protected $appends = ['latitude', 'longitude'];

    /**
     * Get latitude (example using geocoding service)
     */
    public function getLatitudeAttribute()
    {
        // Implement your geocoding logic
        return $this->geocode()['lat'] ?? null;
    }

    /**
     * Get longitude
     */
    public function getLongitudeAttribute()
    {
        return $this->geocode()['lng'] ?? null;
    }

    /**
     * Geocode the address
     */
    protected function geocode()
    {
        // Cache the result
        return cache()->remember("address.{$this->id}.geocode", 3600, function() {
            // Call your preferred geocoding service
            // Return ['lat' => ..., 'lng' => ...]
        });
    }
}
```

## Common Use Cases

### E-commerce: Billing and Shipping

```php
// Add custom column via migration
Schema::table('addresses', function (Blueprint $table) {
    $table->enum('address_type', ['billing', 'shipping'])->default('shipping');
});

// Usage
$user->addresses()->create([
    'primary' => '123 Main St',
    'city' => 'Kuala Lumpur',
    'postcode' => '50088',
    'country_id' => $countryId,
    'address_type' => 'billing',
    'is_default' => true,
]);

// Retrieve
$billingAddress = $user->addresses()
    ->where('address_type', 'billing')
    ->where('is_default', true)
    ->first();

$shippingAddress = $user->addresses()
    ->where('address_type', 'shipping')
    ->where('is_default', true)
    ->first();
```

### Multi-location Business

```php
class Company extends Model
{
    use Addressable;

    public function getHeadquarters()
    {
        return $this->addresses()
            ->where('is_default', true)
            ->first();
    }

    public function getBranches()
    {
        return $this->addresses()
            ->where('is_default', false)
            ->get();
    }
}
```

### Address History

```php
// Using soft deletes, you can track address changes
$currentAddress = $user->addresses()->first();
$previousAddresses = $user->addresses()->onlyTrashed()->get();

// Create audit trail
$user->addresses()->create([
    'primary' => 'New Address',
    'city' => 'New City',
    'created_at' => now(),
]);

// Old address automatically soft-deleted when user moves
```

## What's Next?

- [Working with Emails](02-emails.md) - Managing email addresses
- [Working with Phones](03-phones.md) - Managing phone numbers
- [Advanced Queries](06-advanced-queries.md) - Complex query examples
