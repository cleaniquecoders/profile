# Working with Emergency Contacts

Emergency contacts allow you to store important contact information for people who should be reached in case of an emergency, along with their relationship to the primary entity.

## Overview

The `EmergencyContactable` trait provides methods for managing emergency contact information associated with any model. Each emergency contact includes name, relationship type, phone, email, and priority status.

## Setup

Add the `EmergencyContactable` trait to your model, or use the `HasProfile` trait which includes it:

```php
use CleaniqueCoders\Profile\Concerns\EmergencyContactable;
// OR
use CleaniqueCoders\Profile\Concerns\HasProfile;

class User extends Model
{
    use HasProfile; // Includes EmergencyContactable and other profile traits
}
```

## Creating Emergency Contacts

### Basic Creation

```php
$user = User::find(1);

$user->emergencyContacts()->create([
    'name' => 'Jane Doe',
    'relationship_type' => 'spouse',
    'phone' => '+1234567890',
    'email' => 'jane@example.com',
    'notes' => 'Available 24/7',
    'is_primary' => true,
]);
```

### Adding Multiple Contacts

```php
$user->emergencyContacts()->createMany([
    [
        'name' => 'Jane Doe',
        'relationship_type' => 'spouse',
        'phone' => '+1234567890',
        'email' => 'jane@example.com',
        'is_primary' => true,
    ],
    [
        'name' => 'John Smith',
        'relationship_type' => 'friend',
        'phone' => '+0987654321',
        'email' => 'john@example.com',
        'notes' => 'Work colleague, available weekdays',
    ],
    [
        'name' => 'Mary Johnson',
        'relationship_type' => 'mother',
        'phone' => '+1122334455',
        'email' => 'mary@example.com',
    ],
]);
```

## Retrieving Emergency Contacts

### Get All Contacts

```php
$contacts = $user->emergencyContacts;

foreach ($contacts as $contact) {
    echo "{$contact->name} ({$contact->relationship_type}): {$contact->phone}";
}
```

### Get Primary Contact

```php
$primaryContact = $user->primaryEmergencyContact();

if ($primaryContact) {
    echo "Emergency contact: {$primaryContact->name} - {$primaryContact->phone}";
}
```

### Get Contacts by Relationship

```php
$familyContacts = $user->getEmergencyContactsByRelationship('spouse');

foreach ($familyContacts as $contact) {
    echo "{$contact->name}: {$contact->phone}";
}
```

### Check if Contacts Exist

```php
if ($user->hasEmergencyContacts()) {
    echo 'Emergency contacts are configured';
} else {
    echo 'No emergency contacts found';
}
```

## Updating Emergency Contacts

```php
$contact = $user->emergencyContacts()->first();

$contact->update([
    'phone' => '+9998887777',
    'email' => 'newemail@example.com',
    'notes' => 'Updated contact information',
]);
```

## Query Scopes

### Get Primary Contact

```php
$primary = $user->emergencyContacts()->primary()->first();
```

### Filter by Relationship Type

```php
$parents = $user->emergencyContacts()->relationship('parent')->get();
$spouse = $user->emergencyContacts()->relationship('spouse')->first();
```

### Combining Queries

```php
$primarySpouse = $user->emergencyContacts()
    ->primary()
    ->relationship('spouse')
    ->first();
```

## Deleting Emergency Contacts

```php
// Delete specific contact
$contact = $user->emergencyContacts()->first();
$contact->delete();

// Delete all contacts
$user->emergencyContacts()->delete();

// Soft delete is enabled by default
// To permanently delete:
$contact->forceDelete();
```

## Relationship Types

Common relationship types include:

| Type | Description |
|------|-------------|
| `spouse` | Spouse or partner |
| `partner` | Domestic partner |
| `parent` | Parent (general) |
| `father` | Father |
| `mother` | Mother |
| `sibling` | Sibling (general) |
| `brother` | Brother |
| `sister` | Sister |
| `child` | Child (general) |
| `son` | Son |
| `daughter` | Daughter |
| `grandparent` | Grandparent |
| `grandchild` | Grandchild |
| `friend` | Friend |
| `colleague` | Work colleague |
| `neighbor` | Neighbor |
| `guardian` | Legal guardian |
| `other` | Other relationship |

## Complete Example

```php
use App\Models\User;
use App\Models\Employee;

// Add emergency contacts to a user
$user = User::find(1);

$user->emergencyContacts()->createMany([
    [
        'name' => 'Sarah Johnson',
        'relationship_type' => 'spouse',
        'phone' => '+1234567890',
        'email' => 'sarah@example.com',
        'notes' => 'Primary contact - available anytime',
        'is_primary' => true,
    ],
    [
        'name' => 'Robert Johnson',
        'relationship_type' => 'father',
        'phone' => '+0987654321',
        'email' => 'robert@example.com',
        'notes' => 'Secondary contact',
    ],
]);

// Display emergency contacts
echo "Emergency Contacts for {$user->name}:\n";
foreach ($user->emergencyContacts as $contact) {
    $primary = $contact->is_primary ? ' (PRIMARY)' : '';
    echo sprintf(
        "- %s (%s): %s%s\n",
        $contact->name,
        $contact->relationship_type,
        $contact->phone,
        $primary
    );
}

// Get primary contact
$primary = $user->primaryEmergencyContact();
if ($primary) {
    echo "\nIn case of emergency, contact: {$primary->name} at {$primary->phone}\n";
}

// Check for family contacts
$family = $user->emergencyContacts()
    ->whereIn('relationship_type', ['spouse', 'parent', 'sibling'])
    ->get();

echo "\nFamily contacts: {$family->count()}\n";
```

## Best Practices

### Validation

Always validate emergency contact data:

```php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'relationship_type' => 'required|string|max:50',
    'phone' => 'required|string|max:20',
    'email' => 'nullable|email|max:255',
    'notes' => 'nullable|string|max:1000',
    'is_primary' => 'boolean',
]);

$user->emergencyContacts()->create($validated);
```

### Phone Number Formatting

Standardize phone numbers:

```php
use libphonenumber\PhoneNumberUtil;

$phoneUtil = PhoneNumberUtil::getInstance();
$phoneNumber = $phoneUtil->parse($request->phone, 'US');
$formatted = $phoneUtil->format($phoneNumber, PhoneNumberFormat::E164);

$user->emergencyContacts()->create([
    'name' => $request->name,
    'relationship_type' => $request->relationship_type,
    'phone' => $formatted,
    'email' => $request->email,
]);
```

### Primary Contact Management

Ensure only one primary contact:

```php
DB::transaction(function () use ($user, $contactId) {
    // Remove primary flag from all contacts
    $user->emergencyContacts()->update(['is_primary' => false]);

    // Set new primary
    $user->emergencyContacts()->find($contactId)->update(['is_primary' => true]);
});
```

### Required Contacts

Enforce minimum number of emergency contacts:

```php
// In your model
public function hasMinimumEmergencyContacts(): bool
{
    return $this->emergencyContacts()->count() >= 2;
}

// In your validation
if (!$user->hasMinimumEmergencyContacts()) {
    throw new \Exception('At least 2 emergency contacts are required');
}
```

## Privacy and Security

### Data Encryption

Consider encrypting sensitive information:

```php
use Illuminate\Support\Facades\Crypt;

$user->emergencyContacts()->create([
    'name' => $request->name,
    'relationship_type' => $request->relationship_type,
    'phone' => Crypt::encryptString($request->phone),
    'email' => Crypt::encryptString($request->email),
]);

// When retrieving
$contact = $user->emergencyContacts()->first();
$phone = Crypt::decryptString($contact->phone);
```

### Access Control

Restrict who can view emergency contacts:

```php
// In your policy
public function viewEmergencyContacts(User $user, User $model): bool
{
    return $user->id === $model->id
        || $user->hasRole('admin')
        || $user->hasRole('hr');
}
```

## Related Documentation

- [Working with Phones](03-phones.md) - Phone number management
- [Working with Emails](02-emails.md) - Email management
- [Advanced Queries](06-advanced-queries.md) - Complex query patterns
- [Best Practices](07-best-practices.md) - Security and validation
