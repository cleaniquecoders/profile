# Working with Emails

Learn how to manage email addresses using the `Emailable` trait.

## Setup

Add the `Emailable` trait to your model:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\Emailable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Emailable;
}
```

## Creating Emails

### Basic Email Creation

```php
$user = User::find(1);

$email = $user->emails()->create([
    'email' => 'john.doe@example.com',
    'is_default' => true,
]);
```

### Creating Multiple Emails

```php
// Personal email
$user->emails()->create([
    'email' => 'john.personal@gmail.com',
    'is_default' => true,
]);

// Work email
$user->emails()->create([
    'email' => 'john.doe@company.com',
    'is_default' => false,
]);

// Alternative email
$user->emails()->create([
    'email' => 'j.doe@alternative.com',
    'is_default' => false,
]);
```

## Retrieving Emails

### Get All Emails

```php
$emails = $user->emails;

foreach ($emails as $email) {
    echo $email->email;
}
```

### Get Default Email

```php
$defaultEmail = $user->emails()->where('is_default', true)->first();

// Get just the email address
$emailAddress = $user->emails()
    ->where('is_default', true)
    ->first()
    ->email;
```

### Check if Email Exists

```php
$hasEmail = $user->emails()
    ->where('email', 'john@example.com')
    ->exists();
```

## Updating Emails

### Update Email Address

```php
$email = $user->emails()->first();

$email->update([
    'email' => 'newemail@example.com',
]);
```

### Set as Default

```php
// Remove default flag from all emails
$user->emails()->update(['is_default' => false]);

// Set specific email as default
$email = $user->emails()->where('email', 'john@example.com')->first();
$email->update(['is_default' => true]);
```

## Deleting Emails

### Soft Delete

```php
$email = $user->emails()->first();
$email->delete();
```

### Restore

```php
$email = $user->emails()->withTrashed()->find($emailId);
$email->restore();
```

### Permanent Delete

```php
$email->forceDelete();
```

## Advanced Patterns

### Helper Methods

```php
class User extends Model
{
    use Emailable;

    /**
     * Get the default email address
     */
    public function getDefaultEmailAddress(): ?string
    {
        return $this->emails()
            ->where('is_default', true)
            ->first()
            ?->email;
    }

    /**
     * Get all email addresses as array
     */
    public function getEmailAddresses(): array
    {
        return $this->emails()
            ->pluck('email')
            ->toArray();
    }

    /**
     * Check if user has specific email
     */
    public function hasEmail(string $email): bool
    {
        return $this->emails()
            ->where('email', $email)
            ->exists();
    }

    /**
     * Add email if it doesn't exist
     */
    public function addEmail(string $email, bool $isDefault = false): void
    {
        if (!$this->hasEmail($email)) {
            if ($isDefault) {
                $this->emails()->update(['is_default' => false]);
            }

            $this->emails()->create([
                'email' => $email,
                'is_default' => $isDefault,
            ]);
        }
    }
}
```

### Validation

```php
use Illuminate\Http\Request;

public function storeEmail(Request $request, User $user)
{
    $validated = $request->validate([
        'email' => [
            'required',
            'email',
            'max:255',
            // Ensure email is unique for this user
            'unique:emails,email,NULL,id,emailable_type,' . get_class($user) . ',emailable_id,' . $user->id,
        ],
        'is_default' => 'boolean',
    ]);

    // If setting as default, remove default from others
    if ($validated['is_default'] ?? false) {
        $user->emails()->update(['is_default' => false]);
    }

    return $user->emails()->create($validated);
}
```

### Email Verification Integration

```php
// Add column via migration
Schema::table('emails', function (Blueprint $table) {
    $table->timestamp('verified_at')->nullable();
});

// Extended model
class Email extends \CleaniqueCoders\Profile\Models\Email
{
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Check if email is verified
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Mark email as verified
     */
    public function markAsVerified(): void
    {
        $this->update(['verified_at' => now()]);
    }
}

// Usage
$email = $user->emails()->first();

if (!$email->isVerified()) {
    // Send verification email
    $email->markAsVerified();
}
```

## Common Use Cases

### Newsletter Subscription

```php
// Add columns via migration
Schema::table('emails', function (Blueprint $table) {
    $table->boolean('newsletter_subscribed')->default(false);
    $table->timestamp('subscribed_at')->nullable();
});

// Usage
$email = $user->emails()->where('is_default', true)->first();

$email->update([
    'newsletter_subscribed' => true,
    'subscribed_at' => now(),
]);

// Get all subscribed emails
$subscribedEmails = Email::where('newsletter_subscribed', true)->get();
```

### Contact Management System

```php
class Contact extends Model
{
    use Emailable;

    /**
     * Get work emails
     */
    public function getWorkEmails()
    {
        return $this->emails()
            ->where('email', 'LIKE', '%@company.com')
            ->get();
    }

    /**
     * Get personal emails
     */
    public function getPersonalEmails()
    {
        return $this->emails()
            ->where('email', 'NOT LIKE', '%@company.com')
            ->get();
    }
}
```

### Email Domain Filtering

```php
// Get all emails from specific domain
$companyEmails = $user->emails()
    ->where('email', 'LIKE', '%@company.com')
    ->get();

// Get all Gmail addresses
$gmailAddresses = $user->emails()
    ->where('email', 'LIKE', '%@gmail.com')
    ->get();

// Get all corporate emails (not free providers)
$corporateEmails = $user->emails()
    ->where('email', 'NOT LIKE', '%@gmail.com')
    ->where('email', 'NOT LIKE', '%@yahoo.com')
    ->where('email', 'NOT LIKE', '%@hotmail.com')
    ->get();
```

### Notification Routing

```php
class User extends Model
{
    use Emailable;

    /**
     * Route notifications to the default email
     */
    public function routeNotificationForMail($notification)
    {
        return $this->getDefaultEmailAddress();
    }

    /**
     * Get all notification emails
     */
    public function getNotificationEmails()
    {
        // Could add a notification_enabled column
        return $this->emails()
            ->where('notification_enabled', true)
            ->pluck('email')
            ->toArray();
    }
}
```

### Bulk Operations

```php
// Add multiple emails at once
$emails = [
    'john@personal.com',
    'john@work.com',
    'john@alternative.com',
];

foreach ($emails as $index => $email) {
    $user->emails()->create([
        'email' => $email,
        'is_default' => $index === 0,
    ]);
}

// Update all emails to non-default
$user->emails()->update(['is_default' => false]);

// Delete all non-default emails
$user->emails()->where('is_default', false)->delete();
```

## What's Next?

- [Working with Phones](03-phones.md) - Managing phone numbers
- [Working with Websites](04-websites.md) - Managing website URLs
- [Advanced Queries](06-advanced-queries.md) - Complex query examples
