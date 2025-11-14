# Advanced Queries

Complex query examples and patterns for working with the Profile package.

## Cross-Model Queries

### Find All Entities with Specific Email

```php
use CleaniqueCoders\Profile\Models\Email;

// Find all entities with a specific email
$email = Email::where('email', 'john@example.com')->first();
$owner = $email->emailable; // Could be User, Company, etc.

// Get all entities with Gmail addresses
$gmailUsers = Email::where('email', 'LIKE', '%@gmail.com')
    ->get()
    ->map(fn($email) => $email->emailable)
    ->unique('id');
```

### Find All Entities in a City

```php
use CleaniqueCoders\Profile\Models\Address;

$entities = Address::where('city', 'Kuala Lumpur')
    ->with('addressable')
    ->get()
    ->pluck('addressable')
    ->unique('id');
```

### Find All Entities with Mobile Phones

```php
use CleaniqueCoders\Profile\Models\Phone;
use CleaniqueCoders\Profile\Models\PhoneType;

$entities = Phone::where('phone_type_id', PhoneType::MOBILE)
    ->with('phoneable')
    ->get()
    ->pluck('phoneable')
    ->unique('id');
```

## Eager Loading

### Load All Profile Information

```php
$user = User::with([
    'addresses.country',
    'emails',
    'phones.type',
    'websites',
    'banks.bank'
])->find(1);

// Access without additional queries
$user->addresses->each(function($address) {
    echo $address->city;
    echo $address->country->name;
});
```

### Conditional Eager Loading

```php
$users = User::with([
    'addresses' => function($query) {
        $query->where('is_default', true);
    },
    'phones' => function($query) {
        $query->mobile()->where('is_default', true);
    },
    'emails' => function($query) {
        $query->where('is_default', true);
    }
])->get();
```

### Lazy Eager Loading

```php
$users = User::all();

// Later, load profile information
$users->load([
    'addresses.country',
    'phones.type',
    'emails',
]);
```

## Aggregates and Counts

### Count Profile Items

```php
$user = User::withCount([
    'addresses',
    'emails',
    'phones',
    'websites',
    'banks'
])->find(1);

echo "Addresses: {$user->addresses_count}";
echo "Emails: {$user->emails_count}";
echo "Phones: {$user->phones_count}";
```

### Conditional Counts

```php
$users = User::withCount([
    'phones as mobile_phones_count' => function($query) {
        $query->mobile();
    },
    'addresses as malaysian_addresses_count' => function($query) {
        $query->whereHas('country', function($q) {
            $q->where('code', 'MY');
        });
    }
])->get();
```

## Existence Queries

### Users with Complete Profiles

```php
$completeProfiles = User::whereHas('addresses')
    ->whereHas('emails')
    ->whereHas('phones')
    ->get();
```

### Users Missing Information

```php
// Users without addresses
$noAddress = User::whereDoesntHave('addresses')->get();

// Users without mobile phone
$noMobile = User::whereDoesntHave('phones', function($query) {
    $query->mobile();
})->get();

// Users without default email
$noDefaultEmail = User::whereDoesntHave('emails', function($query) {
    $query->where('is_default', true);
})->get();
```

## Complex Filtering

### Filter by Multiple Criteria

```php
$users = User::whereHas('addresses', function($query) {
        $query->where('city', 'Kuala Lumpur')
              ->whereHas('country', function($q) {
                  $q->where('code', 'MY');
              });
    })
    ->whereHas('phones', function($query) {
        $query->mobile();
    })
    ->whereHas('emails', function($query) {
        $query->where('is_default', true);
    })
    ->get();
```

### Search Across Profile Fields

```php
$searchTerm = 'John';

$results = User::where(function($query) use ($searchTerm) {
        $query->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('emails', function($q) use ($searchTerm) {
                  $q->where('email', 'LIKE', "%{$searchTerm}%");
              })
              ->orWhereHas('phones', function($q) use ($searchTerm) {
                  $q->where('phone_number', 'LIKE', "%{$searchTerm}%");
              })
              ->orWhereHas('addresses', function($q) use ($searchTerm) {
                  $q->where('primary', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('city', 'LIKE', "%{$searchTerm}%");
              });
    })
    ->get();
```

## Scopes and Reusable Queries

### Custom Scopes

```php
class User extends Model
{
    use HasProfile;

    /**
     * Scope for users with complete profile
     */
    public function scopeCompleteProfile($query)
    {
        return $query->whereHas('addresses')
                     ->whereHas('emails')
                     ->whereHas('phones');
    }

    /**
     * Scope for users in specific country
     */
    public function scopeInCountry($query, string $countryCode)
    {
        return $query->whereHas('addresses.country', function($q) use ($countryCode) {
            $q->where('code', $countryCode);
        });
    }

    /**
     * Scope for users with mobile phone
     */
    public function scopeHasMobile($query)
    {
        return $query->whereHas('phones', function($q) {
            $q->mobile();
        });
    }
}

// Usage
$users = User::completeProfile()->inCountry('MY')->hasMobile()->get();
```

## Bulk Operations

### Update All Defaults

```php
// Set first address as default for all users without default
User::whereDoesntHave('addresses', function($query) {
        $query->where('is_default', true);
    })
    ->each(function($user) {
        $firstAddress = $user->addresses()->first();
        if ($firstAddress) {
            $firstAddress->update(['is_default' => true]);
        }
    });
```

### Clean Up Duplicates

```php
// Remove duplicate emails for each user
User::with('emails')->each(function($user) {
    $seen = [];

    $user->emails->each(function($email) use (&$seen) {
        if (in_array($email->email, $seen)) {
            $email->delete();
        } else {
            $seen[] = $email->email;
        }
    });
});
```

## Performance Optimization

### Chunking Large Datasets

```php
User::with(['addresses', 'emails', 'phones'])
    ->chunk(100, function($users) {
        foreach ($users as $user) {
            // Process each user
        }
    });
```

### Select Specific Columns

```php
$users = User::select(['id', 'name'])
    ->with([
        'addresses:id,addressable_id,addressable_type,primary,city',
        'emails:id,emailable_id,emailable_type,email'
    ])
    ->get();
```

### Caching Results

```php
$userProfile = cache()->remember("user.{$userId}.profile", 3600, function() use ($userId) {
    return User::with([
        'addresses.country',
        'emails',
        'phones.type',
        'websites',
        'banks.bank'
    ])->find($userId);
});
```

## What's Next?

- [Best Practices](07-best-practices.md) - Tips and recommendations
- [API Reference](../04-api-reference/README.md) - Complete method documentation
