# Working with Phones

Learn how to manage phone numbers using the `Phoneable` trait.

## Setup

Add the `Phoneable` trait to your model:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\Phoneable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Phoneable;
}
```

## Phone Types

The package provides predefined phone types:

```php
use CleaniqueCoders\Profile\Models\PhoneType;

PhoneType::HOME    // = 1 - Home phone
PhoneType::MOBILE  // = 2 - Mobile/cell phone
PhoneType::OFFICE  // = 3 - Office phone
PhoneType::OTHER   // = 4 - Other phone type
PhoneType::FAX     // = 5 - Fax number
```

## Creating Phone Numbers

### Basic Phone Creation

```php
use CleaniqueCoders\Profile\Models\PhoneType;

$user = User::find(1);

$phone = $user->phones()->create([
    'phone_number' => '+60123456789',
    'phone_type_id' => PhoneType::MOBILE,
    'is_default' => true,
]);
```

### Creating Multiple Phone Numbers

```php
// Mobile phone
$user->phones()->create([
    'phone_number' => '+60123456789',
    'phone_type_id' => PhoneType::MOBILE,
    'is_default' => true,
]);

// Home phone
$user->phones()->create([
    'phone_number' => '+60389259167',
    'phone_type_id' => PhoneType::HOME,
    'is_default' => false,
]);

// Office phone
$user->phones()->create([
    'phone_number' => '+60380001000',
    'phone_type_id' => PhoneType::OFFICE,
    'is_default' => false,
]);

// Fax number
$user->phones()->create([
    'phone_number' => '+60380001001',
    'phone_type_id' => PhoneType::FAX,
    'is_default' => false,
]);
```

### International Format

```php
// Malaysian number
$user->phones()->create([
    'phone_number' => '+60123456789',
    'phone_type_id' => PhoneType::MOBILE,
]);

// US number
$user->phones()->create([
    'phone_number' => '+1 (555) 123-4567',
    'phone_type_id' => PhoneType::MOBILE,
]);

// UK number
$user->phones()->create([
    'phone_number' => '+44 20 7946 0958',
    'phone_type_id' => PhoneType::OFFICE,
]);
```

## Retrieving Phone Numbers

### Get All Phones

```php
$phones = $user->phones;

foreach ($phones as $phone) {
    echo $phone->phone_number;
    echo $phone->type->name; // Home, Mobile, Office, etc.
}
```

### Get with Type Information

```php
$phones = $user->phones()->with('type')->get();

foreach ($phones as $phone) {
    echo "{$phone->type->name}: {$phone->phone_number}";
}
```

### Get Default Phone

```php
$defaultPhone = $user->phones()->where('is_default', true)->first();
```

### Get by Phone Type (Using Scopes)

```php
// Get mobile phones
$mobilePhones = $user->phones()->mobile()->get();

// Get home phones
$homePhones = $user->phones()->home()->get();

// Get office phones
$officePhones = $user->phones()->office()->get();

// Get fax numbers
$faxNumbers = $user->phones()->fax()->get();

// Get other phone numbers
$otherPhones = $user->phones()->other()->get();
```

### Get First Phone of Each Type

```php
$mobile = $user->phones()->mobile()->first();
$home = $user->phones()->home()->first();
$office = $user->phones()->office()->first();
```

## Updating Phone Numbers

### Update Phone Number

```php
$phone = $user->phones()->first();

$phone->update([
    'phone_number' => '+60198765432',
]);
```

### Change Phone Type

```php
$phone->update([
    'phone_type_id' => PhoneType::OFFICE,
]);
```

### Set as Default

```php
// Remove default flag from all phones
$user->phones()->update(['is_default' => false]);

// Set specific phone as default
$phone = $user->phones()->mobile()->first();
$phone->update(['is_default' => true]);
```

## Deleting Phone Numbers

### Soft Delete

```php
$phone = $user->phones()->first();
$phone->delete();
```

### Restore

```php
$phone = $user->phones()->withTrashed()->find($phoneId);
$phone->restore();
```

### Permanent Delete

```php
$phone->forceDelete();
```

## Advanced Patterns

### Helper Methods

```php
class User extends Model
{
    use Phoneable;

    /**
     * Get the default phone number
     */
    public function getDefaultPhoneNumber(): ?string
    {
        return $this->phones()
            ->where('is_default', true)
            ->first()
            ?->phone_number;
    }

    /**
     * Get primary mobile number
     */
    public function getMobileNumber(): ?string
    {
        return $this->phones()
            ->mobile()
            ->where('is_default', true)
            ->orWhere(function($query) {
                $query->mobile()->oldest();
            })
            ->first()
            ?->phone_number;
    }

    /**
     * Get office contact number
     */
    public function getOfficeNumber(): ?string
    {
        return $this->phones()
            ->office()
            ->first()
            ?->phone_number;
    }

    /**
     * Get all phone numbers as array
     */
    public function getPhoneNumbers(): array
    {
        return $this->phones()
            ->pluck('phone_number')
            ->toArray();
    }

    /**
     * Check if user has mobile phone
     */
    public function hasMobilePhone(): bool
    {
        return $this->phones()->mobile()->exists();
    }
}
```

### Validation

```php
use Illuminate\Http\Request;

public function storePhone(Request $request, User $user)
{
    $validated = $request->validate([
        'phone_number' => [
            'required',
            'string',
            'max:20',
            // You might want to use a phone validation package
            'regex:/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}$/',
        ],
        'phone_type_id' => 'required|exists:phone_types,id',
        'is_default' => 'boolean',
    ]);

    // If setting as default, remove default from others
    if ($validated['is_default'] ?? false) {
        $user->phones()->update(['is_default' => false]);
    }

    return $user->phones()->create($validated);
}
```

### Phone Formatting

```php
class Phone extends \CleaniqueCoders\Profile\Models\Phone
{
    /**
     * Get formatted phone number
     */
    public function getFormattedAttribute(): string
    {
        // Remove all non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $this->phone_number);

        // Format Malaysian mobile numbers
        if (str_starts_with($clean, '60') && strlen($clean) === 11) {
            return '+60 ' . substr($clean, 2, 2) . '-' .
                   substr($clean, 4, 3) . ' ' . substr($clean, 7);
        }

        // Return original if format not recognized
        return $this->phone_number;
    }

    /**
     * Get clickable tel: link
     */
    public function getTelLinkAttribute(): string
    {
        $clean = preg_replace('/[^0-9+]/', '', $this->phone_number);
        return "tel:{$clean}";
    }
}

// Usage
$phone = $user->phones()->first();
echo $phone->formatted; // +60 12-345 6789
echo $phone->tel_link;  // tel:+60123456789
```

## Common Use Cases

### Contact Center Integration

```php
class Customer extends Model
{
    use Phoneable;

    /**
     * Get primary contact number
     */
    public function getPrimaryContact(): ?string
    {
        return $this->getMobileNumber()
            ?? $this->getHomeNumber()
            ?? $this->getOfficeNumber();
    }

    /**
     * Get all contact numbers for calling
     */
    public function getContactNumbers(): array
    {
        return $this->phones()
            ->with('type')
            ->get()
            ->map(function($phone) {
                return [
                    'number' => $phone->phone_number,
                    'type' => $phone->type->name,
                    'is_default' => $phone->is_default,
                ];
            })
            ->sortByDesc('is_default')
            ->values()
            ->toArray();
    }
}
```

### WhatsApp Integration

```php
class User extends Model
{
    use Phoneable;

    /**
     * Get WhatsApp link for mobile number
     */
    public function getWhatsAppLink(): ?string
    {
        $mobile = $this->phones()->mobile()->first();

        if (!$mobile) {
            return null;
        }

        // Clean number (remove spaces, dashes, parentheses)
        $clean = preg_replace('/[^0-9+]/', '', $mobile->phone_number);

        // Remove + for WhatsApp API
        $clean = ltrim($clean, '+');

        return "https://wa.me/{$clean}";
    }
}
```

### SMS Notifications

```php
class User extends Model
{
    use Phoneable;

    /**
     * Route notifications to mobile phone
     */
    public function routeNotificationForSms($notification)
    {
        return $this->getMobileNumber();
    }

    /**
     * Get all SMS-enabled phones
     */
    public function getSmsPhones()
    {
        return $this->phones()
            ->mobile()
            ->pluck('phone_number')
            ->toArray();
    }
}
```

### Emergency Contacts

```php
// Add column via migration
Schema::table('phones', function (Blueprint $table) {
    $table->boolean('is_emergency')->default(false);
    $table->string('emergency_contact_name')->nullable();
});

// Usage
$user->phones()->create([
    'phone_number' => '+60123456789',
    'phone_type_id' => PhoneType::MOBILE,
    'is_emergency' => true,
    'emergency_contact_name' => 'Jane Doe (Sister)',
]);

// Retrieve emergency contacts
$emergencyContacts = $user->phones()
    ->where('is_emergency', true)
    ->get();
```

### Phone Number Verification

```php
// Add column via migration
Schema::table('phones', function (Blueprint $table) {
    $table->timestamp('verified_at')->nullable();
    $table->string('verification_code')->nullable();
});

// Extended model
class Phone extends \CleaniqueCoders\Profile\Models\Phone
{
    /**
     * Send verification code
     */
    public function sendVerificationCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'verification_code' => $code,
        ]);

        // Send SMS with $code

        return $code;
    }

    /**
     * Verify phone number
     */
    public function verify(string $code): bool
    {
        if ($this->verification_code === $code) {
            $this->update([
                'verified_at' => now(),
                'verification_code' => null,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Check if verified
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }
}
```

## What's Next?

- [Working with Websites](04-websites.md) - Managing website URLs
- [Working with Bank Accounts](05-bank-accounts.md) - Managing banking information
- [Advanced Queries](06-advanced-queries.md) - Complex query examples
