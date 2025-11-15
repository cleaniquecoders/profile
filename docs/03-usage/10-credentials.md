# Working with Professional Credentials

Professional credentials allow you to store information about licenses, certifications, diplomas, and other professional qualifications, including their validity periods and verification status.

## Overview

The `Credentialable` trait provides methods for managing professional credentials associated with any model. Each credential includes type, title, issuer, number, issue and expiration dates, and verification status.

## Setup

Add the `Credentialable` trait to your model, or use the `HasProfile` trait which includes it:

```php
use CleaniqueCoders\Profile\Concerns\Credentialable;
// OR
use CleaniqueCoders\Profile\Concerns\HasProfile;

class User extends Model
{
    use HasProfile; // Includes Credentialable and other profile traits
}
```

## Creating Credentials

### Basic Creation

```php
$user = User::find(1);

$user->credentials()->create([
    'type' => 'license',
    'title' => 'Medical License',
    'issuer' => 'State Medical Board',
    'number' => 'ML-123456',
    'issued_at' => '2020-01-01',
    'expires_at' => '2025-12-31',
    'is_verified' => true,
    'notes' => 'Active state medical license',
]);
```

### Adding Multiple Credentials

```php
$user->credentials()->createMany([
    [
        'type' => 'certification',
        'title' => 'AWS Solutions Architect - Professional',
        'issuer' => 'Amazon Web Services',
        'number' => 'AWS-PSA-12345',
        'issued_at' => '2023-06-15',
        'expires_at' => '2026-06-15',
        'is_verified' => true,
    ],
    [
        'type' => 'certification',
        'title' => 'Certified Kubernetes Administrator',
        'issuer' => 'Cloud Native Computing Foundation',
        'number' => 'CKA-2023-67890',
        'issued_at' => '2023-03-20',
        'expires_at' => '2026-03-20',
        'is_verified' => true,
    ],
    [
        'type' => 'degree',
        'title' => 'Bachelor of Science in Computer Science',
        'issuer' => 'State University',
        'issued_at' => '2018-05-20',
        'is_verified' => true,
    ],
]);
```

## Retrieving Credentials

### Get All Credentials

```php
$credentials = $user->credentials;

foreach ($credentials as $credential) {
    echo "{$credential->title} - {$credential->issuer}";
}
```

### Get Active Credentials

```php
$activeCredentials = $user->activeCredentials();

foreach ($activeCredentials as $credential) {
    $expires = $credential->expires_at ? "Expires: {$credential->expires_at->format('Y-m-d')}" : 'No expiration';
    echo "{$credential->title} - {$expires}";
}
```

### Get Expired Credentials

```php
$expiredCredentials = $user->expiredCredentials();

foreach ($expiredCredentials as $credential) {
    echo "{$credential->title} - Expired: {$credential->expires_at->format('Y-m-d')}";
}
```

### Get Credentials by Type

```php
$licenses = $user->getCredentialsByType('license');
$certifications = $user->getCredentialsByType('certification');
$degrees = $user->getCredentialsByType('degree');
```

### Check Credential Status

```php
// Check if has any active credentials
if ($user->hasActiveCredentials()) {
    echo 'User has active credentials';
}

// Check if has specific credential type
if ($user->hasCredential('license')) {
    echo 'User has professional license';
}
```

## Updating Credentials

```php
$credential = $user->credentials()->first();

$credential->update([
    'number' => 'NEW-12345',
    'expires_at' => '2027-12-31',
    'is_verified' => true,
]);
```

## Query Scopes

### Get Verified Credentials

```php
$verified = $user->credentials()->verified()->get();
```

### Filter by Type

```php
$licenses = $user->credentials()->type('license')->get();
$certifications = $user->credentials()->type('certification')->get();
```

### Get Active Credentials

```php
$active = $user->credentials()->active()->get();
```

### Get Expired Credentials

```php
$expired = $user->credentials()->expired()->get();
```

### Combining Scopes

```php
$activeLicenses = $user->credentials()
    ->type('license')
    ->active()
    ->verified()
    ->get();
```

## Deleting Credentials

```php
// Delete specific credential
$credential = $user->credentials()->first();
$credential->delete();

// Delete all credentials
$user->credentials()->delete();

// Soft delete is enabled by default
// To permanently delete:
$credential->forceDelete();
```

## Credential Types

Common credential types include:

| Type | Description | Typical Expiration |
|------|-------------|-------------------|
| `license` | Professional license | Yes |
| `certification` | Professional certification | Yes |
| `diploma` | Educational diploma | No |
| `degree` | Academic degree | No |
| `permit` | Work or professional permit | Yes |
| `accreditation` | Professional accreditation | Yes |
| `registration` | Professional registration | Yes |
| `membership` | Professional membership | Yes |
| `award` | Professional award or recognition | No |

## Complete Example

```php
use App\Models\User;
use Carbon\Carbon;

// Add credentials to a professional
$doctor = User::find(1);

$doctor->credentials()->createMany([
    [
        'type' => 'license',
        'title' => 'Medical License',
        'issuer' => 'State Medical Board',
        'number' => 'MD-12345',
        'issued_at' => '2015-01-01',
        'expires_at' => '2025-12-31',
        'is_verified' => true,
        'notes' => 'State medical license - unrestricted',
    ],
    [
        'type' => 'certification',
        'title' => 'Board Certified - Internal Medicine',
        'issuer' => 'American Board of Internal Medicine',
        'number' => 'ABIM-67890',
        'issued_at' => '2018-06-01',
        'expires_at' => '2028-06-01',
        'is_verified' => true,
    ],
    [
        'type' => 'degree',
        'title' => 'Doctor of Medicine (M.D.)',
        'issuer' => 'Medical School University',
        'issued_at' => '2015-05-20',
        'is_verified' => true,
    ],
]);

// Display active credentials
echo "Active Credentials for Dr. {$doctor->name}:\n\n";
foreach ($doctor->activeCredentials() as $credential) {
    $expiry = $credential->expires_at
        ? " (Expires: {$credential->expires_at->format('M Y')})"
        : '';

    echo "✓ {$credential->title}\n";
    echo "  Issued by: {$credential->issuer}\n";
    echo "  Number: {$credential->number}{$expiry}\n\n";
}

// Check for expiring credentials (within 90 days)
$expiringCredentials = $doctor->credentials()
    ->whereNotNull('expires_at')
    ->where('expires_at', '<=', Carbon::now()->addDays(90))
    ->where('expires_at', '>', Carbon::now())
    ->get();

if ($expiringCredentials->count() > 0) {
    echo "⚠️ Credentials expiring soon:\n";
    foreach ($expiringCredentials as $credential) {
        $daysLeft = Carbon::now()->diffInDays($credential->expires_at);
        echo "- {$credential->title} expires in {$daysLeft} days\n";
    }
}

// Get all licenses
$licenses = $doctor->getCredentialsByType('license');
echo "\nTotal licenses: {$licenses->count()}\n";
```

## Best Practices

### Validation

Always validate credential data:

```php
$validated = $request->validate([
    'type' => 'required|string|in:license,certification,diploma,degree,permit,accreditation',
    'title' => 'required|string|max:255',
    'issuer' => 'required|string|max:255',
    'number' => 'nullable|string|max:100',
    'issued_at' => 'required|date',
    'expires_at' => 'nullable|date|after:issued_at',
    'is_verified' => 'boolean',
    'notes' => 'nullable|string|max:1000',
]);

$user->credentials()->create($validated);
```

### Expiration Monitoring

Set up notifications for expiring credentials:

```php
// In a scheduled command
$expiringCredentials = Credential::query()
    ->whereNotNull('expires_at')
    ->whereBetween('expires_at', [
        Carbon::now(),
        Carbon::now()->addDays(30)
    ])
    ->get();

foreach ($expiringCredentials as $credential) {
    // Notify the credential owner
    $credential->credentialable->notify(
        new CredentialExpiringNotification($credential)
    );
}
```

### Verification Workflow

Implement credential verification:

```php
public function verifyCredential(Credential $credential, User $verifier)
{
    // Perform verification checks
    $verified = $this->checkWithIssuer($credential);

    if ($verified) {
        $credential->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $verifier->id,
        ]);
    }

    return $verified;
}
```

### Document Attachments

Link credentials with documents:

```php
// Store credential and its supporting document
$credential = $user->credentials()->create([
    'type' => 'certification',
    'title' => 'PMP Certification',
    'issuer' => 'PMI',
    'number' => 'PMP-12345',
    'issued_at' => '2023-01-15',
    'expires_at' => '2026-01-15',
]);

// Attach the certificate document
$user->documents()->create([
    'type' => 'certificate',
    'title' => 'PMP Certificate',
    'file_path' => $request->file('certificate')->store('credentials'),
    'file_type' => $request->file('certificate')->extension(),
    'related_credential_id' => $credential->id,
]);
```

### Audit Trail

Track credential changes:

```php
// In your Credential model
protected static function booted()
{
    static::updated(function ($credential) {
        activity()
            ->performedOn($credential)
            ->causedBy(auth()->user())
            ->log('Credential updated');
    });
}
```

## API Integration

### Verify with External Services

```php
public function verifyWithIssuer(Credential $credential)
{
    // Example: Verify AWS certification
    if ($credential->type === 'certification' &&
        Str::contains($credential->issuer, 'AWS')) {

        $client = new AWSCertificationClient();
        $result = $client->verify(
            $credential->number,
            $credential->issued_at
        );

        $credential->update([
            'is_verified' => $result->isValid(),
            'verified_at' => now(),
        ]);

        return $result;
    }
}
```

## Related Documentation

- [Working with Documents](10-documents.md) - Store credential certificates
- [Advanced Queries](06-advanced-queries.md) - Complex query patterns
- [Best Practices](07-best-practices.md) - Security and validation
