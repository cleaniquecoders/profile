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

// Filter by category for broader grouping
$activeRegulatoryCredentials = $user->credentials()
    ->category('regulatory')
    ->active()
    ->verified()
    ->get();

// Get expired educational credentials
$expiredEducation = $user->credentials()
    ->category('education')
    ->expired()
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

| Type | Description | Category | Typical Expiration |
|------|-------------|----------|-------------------|
| `license` | Professional license | Regulatory | Yes |
| `certification` | Professional certification | Regulatory | Yes |
| `diploma` | Educational diploma | Education | No |
| `degree` | Academic degree | Education | No |
| `permit` | Work or professional permit | Regulatory | Yes |
| `accreditation` | Professional accreditation | Regulatory | Yes |
| `registration` | Professional registration | Regulatory | Yes |
| `membership` | Professional membership | Association | Yes |
| `award` | Professional award or recognition | Recognition | No |

## Credential Categories

Credentials are organized into four semantic categories for easier management and filtering:

### Education

Academic qualifications and diplomas:

- `degree` - Bachelor's, Master's, PhD, etc.
- `diploma` - Professional diplomas and certificates

### Regulatory

Compliance and legally-required credentials:

- `license` - Professional licenses (medical, legal, etc.)
- `certification` - Industry certifications
- `permit` - Work permits and authorizations
- `accreditation` - Institutional accreditations
- `registration` - Professional registrations

### Association

Professional memberships:

- `membership` - Professional organization memberships

### Recognition

Awards and honors:

- `award` - Professional awards and recognition

### Filtering by Category

```php
// Get all educational credentials
$education = $user->credentials()->category('education')->get();

// Get regulatory credentials
$regulatory = $user->credentials()->category('regulatory')->get();

// Get professional memberships
$memberships = $user->credentials()->category('association')->get();

// Get awards and recognition
$awards = $user->credentials()->category('recognition')->get();
```

### Combining Category with Other Scopes

```php
// Get active regulatory credentials
$activeRegulatory = $user->credentials()
    ->category('regulatory')
    ->active()
    ->verified()
    ->get();

// Get expiring regulatory credentials (within 90 days)
$expiringRegulatory = $user->credentials()
    ->category('regulatory')
    ->whereNotNull('expires_at')
    ->where('expires_at', '<=', Carbon::now()->addDays(90))
    ->where('expires_at', '>', Carbon::now())
    ->get();
```

### Getting a Credential's Category

```php
$credential = $user->credentials()->first();

// Get the category
$category = $credential->getCategory();
// Returns: 'education', 'regulatory', 'association', or 'recognition'

// Display credentials grouped by category
$groupedCredentials = $user->credentials->groupBy(function ($credential) {
    return $credential->getCategory();
});

foreach ($groupedCredentials as $category => $credentials) {
    echo ucfirst($category) . " Credentials:\n";
    foreach ($credentials as $credential) {
        echo "  - {$credential->title}\n";
    }
    echo "\n";
}
```

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

// Group credentials by category
echo "\n--- Credentials by Category ---\n\n";
$grouped = $doctor->credentials->groupBy(function ($credential) {
    return $credential->getCategory();
});

foreach ($grouped as $category => $credentials) {
    echo ucfirst($category) . " ({$credentials->count()}):\n";
    foreach ($credentials as $credential) {
        echo "  • {$credential->title}\n";
    }
    echo "\n";
}

// Get only regulatory credentials that need renewal
$regulatoryForRenewal = $doctor->credentials()
    ->category('regulatory')
    ->whereNotNull('expires_at')
    ->where('expires_at', '<=', Carbon::now()->addMonths(6))
    ->get();

if ($regulatoryForRenewal->count() > 0) {
    echo "📋 Regulatory credentials requiring renewal:\n";
    foreach ($regulatoryForRenewal as $credential) {
        echo "- {$credential->title} (Expires: {$credential->expires_at->format('M Y')})\n";
    }
}
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
