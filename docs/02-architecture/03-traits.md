# Traits

The Profile package provides several traits that add profile functionality to your models through Laravel's polymorphic relationships.

## Available Traits

### HasProfile

The `HasProfile` trait is a convenience trait that includes all profile traits (core and extended).

**Location**: `CleaniqueCoders\Profile\Concerns\HasProfile`

**Includes**:

**Core Profile Traits:**

- `Addressable` - Manage addresses
- `Emailable` - Manage email addresses
- `Phoneable` - Manage phone numbers
- `Websiteable` - Manage websites

**Extended Profile Traits:**

- `Socialable` - Manage social media profiles
- `EmergencyContactable` - Manage emergency contacts
- `Credentialable` - Manage professional credentials
- `Documentable` - Manage document attachments

**Usage**:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\HasProfile;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasProfile;
}
```

**When to Use**:

- When your model needs comprehensive profile information
- For user accounts, customer profiles, employee records
- When you want a complete profile solution with all features

### Addressable

Adds the ability to manage multiple addresses.

**Location**: `CleaniqueCoders\Profile\Concerns\Addressable`

**Provides**:

- `addresses()` - MorphMany relationship to Address model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Addressable;

class Company extends Model
{
    use Addressable;
}

// Create an address
$company->addresses()->create([
    'primary' => '123 Business St',
    'city' => 'Kuala Lumpur',
    'postcode' => '50088',
    'country_id' => 1,
]);

// Get all addresses
$addresses = $company->addresses;
```

**When to Use**:

- Physical locations need to be tracked
- Billing and shipping addresses
- Office locations, branch addresses

### Emailable

Adds the ability to manage multiple email addresses.

**Location**: `CleaniqueCoders\Profile\Concerns\Emailable`

**Provides**:

- `emails()` - MorphMany relationship to Email model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Emailable;

class Contact extends Model
{
    use Emailable;
}

// Create an email
$contact->emails()->create([
    'email' => 'john@example.com',
    'is_default' => true,
]);

// Get all emails
$emails = $contact->emails;

// Get default email
$defaultEmail = $contact->emails()->where('is_default', true)->first();
```

**When to Use**:

- Multiple email addresses per entity
- Primary and secondary emails
- Work and personal emails

### Phoneable

Adds the ability to manage multiple phone numbers with types.

**Location**: `CleaniqueCoders\Profile\Concerns\Phoneable`

**Provides**:

- `phones()` - MorphMany relationship to Phone model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Phoneable;
use CleaniqueCoders\Profile\Models\PhoneType;

class Customer extends Model
{
    use Phoneable;
}

// Create phone numbers
$customer->phones()->create([
    'phone_number' => '+60123456789',
    'is_default' => true,
    'phone_type_id' => PhoneType::MOBILE,
]);

$customer->phones()->create([
    'phone_number' => '+60380001000',
    'is_default' => false,
    'phone_type_id' => PhoneType::OFFICE,
]);

// Get all phones
$phones = $customer->phones;

// Get mobile phones only
$mobilePhones = $customer->phones()->mobile()->get();

// Get home phones only
$homePhones = $customer->phones()->home()->get();

// Get office phones only
$officePhones = $customer->phones()->office()->get();
```

**When to Use**:

- Contact information with multiple numbers
- Different phone types (mobile, home, office, fax)
- Primary and backup phone numbers

**Available Phone Types**:

- `PhoneType::HOME` - Home phone
- `PhoneType::MOBILE` - Mobile/cell phone
- `PhoneType::OFFICE` - Office phone
- `PhoneType::OTHER` - Other phone type
- `PhoneType::FAX` - Fax number

### Websiteable

Adds the ability to manage multiple website URLs.

**Location**: `CleaniqueCoders\Profile\Concerns\Websiteable`

**Provides**:

- `websites()` - MorphMany relationship to Website model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Websiteable;

class Organization extends Model
{
    use Websiteable;
}

// Create websites
$organization->websites()->create([
    'url' => 'https://example.com',
    'is_default' => true,
]);

$organization->websites()->create([
    'url' => 'https://shop.example.com',
    'is_default' => false,
]);

// Get all websites
$websites = $organization->websites;

// Get default website
$defaultWebsite = $organization->websites()->where('is_default', true)->first();
```

**When to Use**:

- Company websites
- Social media profiles
- Portfolio links
- Multiple web properties

### Bankable

Adds the ability to manage bank account information.

**Location**: `CleaniqueCoders\Profile\Concerns\Bankable`

**Provides**:

- `banks()` - MorphMany relationship to BankAccount model
- `bank()` - BelongsTo relationship to Bank model

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Bankable;

class Employee extends Model
{
    use Bankable;
}

// Create bank account
$employee->banks()->create([
    'bank_id' => 1,
    'account_number' => '1234567890',
    'account_holder_name' => 'John Doe',
]);

// Get all bank accounts
$bankAccounts = $employee->banks;

// Get with bank details
$accounts = $employee->banks()->with('bank')->get();
```

**When to Use**:

- Payroll information
- Payment processing
- Vendor payment details
- Direct deposit setup

### Socialable

Adds the ability to manage social media profiles.

**Location**: `CleaniqueCoders\Profile\Concerns\Socialable`

**Provides**:

- `socialMedia()` - MorphMany relationship to SocialMedia model
- `primarySocialMedia()` - Get primary social media account
- `getSocialMediaByPlatform(string $platform)` - Get account for specific platform
- `hasSocialMedia(string $platform = null)` - Check if social media exists

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Socialable;

class User extends Model
{
    use Socialable;
}

// Create social media account
$user->socialMedia()->create([
    'platform' => 'linkedin',
    'username' => 'johndoe',
    'url' => 'https://linkedin.com/in/johndoe',
    'is_verified' => true,
    'is_primary' => true,
]);

// Get LinkedIn account
$linkedin = $user->getSocialMediaByPlatform('linkedin');

// Check if has GitHub
if ($user->hasSocialMedia('github')) {
    // User has GitHub account
}
```

**When to Use**:

- Professional networking profiles
- Developer portfolios
- Social media integration
- Content creator profiles

### EmergencyContactable

Adds the ability to manage emergency contact information.

**Location**: `CleaniqueCoders\Profile\Concerns\EmergencyContactable`

**Provides**:

- `emergencyContacts()` - MorphMany relationship to EmergencyContact model
- `primaryEmergencyContact()` - Get primary emergency contact
- `getEmergencyContactsByRelationship(string $type)` - Get contacts by relationship type
- `hasEmergencyContacts()` - Check if emergency contacts exist

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\EmergencyContactable;

class Employee extends Model
{
    use EmergencyContactable;
}

// Create emergency contact
$employee->emergencyContacts()->create([
    'name' => 'Jane Doe',
    'relationship_type' => 'spouse',
    'phone' => '+1234567890',
    'email' => 'jane@example.com',
    'is_primary' => true,
]);

// Get primary contact
$primary = $employee->primaryEmergencyContact();

// Get family contacts
$family = $employee->getEmergencyContactsByRelationship('spouse');
```

**When to Use**:

- Employee records
- Student information systems
- Healthcare applications
- Emergency preparedness

### Credentialable

Adds the ability to manage professional credentials, licenses, and certifications.

**Location**: `CleaniqueCoders\Profile\Concerns\Credentialable`

**Provides**:

- `credentials()` - MorphMany relationship to Credential model
- `activeCredentials()` - Get non-expired credentials
- `expiredCredentials()` - Get expired credentials
- `getCredentialsByType(string $type)` - Get credentials by type
- `hasActiveCredentials()` - Check if has active credentials
- `hasCredential(string $type)` - Check if has specific credential type

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Credentialable;

class Professional extends Model
{
    use Credentialable;
}

// Create credential
$professional->credentials()->create([
    'type' => 'license',
    'title' => 'Medical License',
    'issuer' => 'State Medical Board',
    'number' => 'ML-123456',
    'issued_at' => '2020-01-01',
    'expires_at' => '2025-12-31',
    'is_verified' => true,
]);

// Get active credentials
$active = $professional->activeCredentials();

// Check for licenses
if ($professional->hasCredential('license')) {
    // Has professional license
}
```

**When to Use**:

- Professional licensing
- Certification tracking
- Compliance management
- Educational credentials

### Documentable

Adds the ability to manage document attachments.

**Location**: `CleaniqueCoders\Profile\Concerns\Documentable`

**Provides**:

- `documents()` - MorphMany relationship to Document model
- `activeDocuments()` - Get non-expired documents
- `expiredDocuments()` - Get expired documents
- `getDocumentsByType(string $type)` - Get documents by type
- `hasActiveDocuments()` - Check if has active documents
- `hasDocument(string $type)` - Check if has specific document type

**Usage**:

```php
use CleaniqueCoders\Profile\Concerns\Documentable;

class Applicant extends Model
{
    use Documentable;
}

// Create document
$applicant->documents()->create([
    'type' => 'passport',
    'title' => 'US Passport',
    'file_path' => 'documents/passport.pdf',
    'file_type' => 'pdf',
    'file_size' => 2048576,
    'issued_at' => '2020-01-01',
    'expires_at' => '2030-01-01',
    'is_verified' => true,
]);

// Get active documents
$active = $applicant->activeDocuments();

// Check for passport
if ($applicant->hasDocument('passport')) {
    // Has passport on file
}
```

**When to Use**:

- Document management systems
- Identity verification
- Compliance documentation
- File attachments

## Combining Traits

You can use traits individually or in combination:

### Example 1: Basic Contact Information

```php
class Contact extends Model
{
    use Emailable, Phoneable;
}
```

### Example 2: Full Business Profile

```php
class Business extends Model
{
    use Addressable, Emailable, Phoneable, Websiteable, Bankable;
}

// Or simply use HasProfile for comprehensive features:
class Business extends Model
{
    use HasProfile; // Includes all profile traits
}
```

### Example 3: Location Only

```php
class Store extends Model
{
    use Addressable, Phoneable;
}
```

### Example 4: Professional Profile

```php
class Professional extends Model
{
    use Emailable, Phoneable, Socialable, Credentialable, Documentable;
}
```

### Example 5: Employee Profile

```php
class Employee extends Model
{
    use HasProfile; // Full profile including emergency contacts, credentials, and documents
}
```

### Example 6: Applicant Profile

```php
class Applicant extends Model
{
    use Emailable, Phoneable, Documentable, Credentialable;
}
```

## Trait Methods Summary

### Core Profile Traits

| Trait | Method | Return Type | Description |
|-------|--------|-------------|-------------|
| `Addressable` | `addresses()` | `MorphMany` | Get all addresses |
| `Emailable` | `emails()` | `MorphMany` | Get all emails |
| `Phoneable` | `phones()` | `MorphMany` | Get all phones |
| `Websiteable` | `websites()` | `MorphMany` | Get all websites |
| `Bankable` | `banks()` | `MorphMany` | Get all bank accounts |
| `Bankable` | `bank()` | `BelongsTo` | Get associated bank |

### Extended Profile Traits

| Trait | Method | Return Type | Description |
|-------|--------|-------------|-------------|
| `Socialable` | `socialMedia()` | `MorphMany` | Get all social media accounts |
| `Socialable` | `primarySocialMedia()` | `SocialMedia\|null` | Get primary social media account |
| `Socialable` | `getSocialMediaByPlatform($platform)` | `SocialMedia\|null` | Get account for specific platform |
| `Socialable` | `hasSocialMedia($platform)` | `bool` | Check if social media exists |
| `EmergencyContactable` | `emergencyContacts()` | `MorphMany` | Get all emergency contacts |
| `EmergencyContactable` | `primaryEmergencyContact()` | `EmergencyContact\|null` | Get primary emergency contact |
| `EmergencyContactable` | `getEmergencyContactsByRelationship($type)` | `Collection` | Get contacts by relationship |
| `EmergencyContactable` | `hasEmergencyContacts()` | `bool` | Check if contacts exist |
| `Credentialable` | `credentials()` | `MorphMany` | Get all credentials |
| `Credentialable` | `activeCredentials()` | `Collection` | Get non-expired credentials |
| `Credentialable` | `expiredCredentials()` | `Collection` | Get expired credentials |
| `Credentialable` | `getCredentialsByType($type)` | `Collection` | Get credentials by type |
| `Credentialable` | `hasActiveCredentials()` | `bool` | Check if has active credentials |
| `Credentialable` | `hasCredential($type)` | `bool` | Check for specific credential type |
| `Documentable` | `documents()` | `MorphMany` | Get all documents |
| `Documentable` | `activeDocuments()` | `Collection` | Get non-expired documents |
| `Documentable` | `expiredDocuments()` | `Collection` | Get expired documents |
| `Documentable` | `getDocumentsByType($type)` | `Collection` | Get documents by type |
| `Documentable` | `hasActiveDocuments()` | `bool` | Check if has active documents |
| `Documentable` | `hasDocument($type)` | `bool` | Check for specific document type |

### Combined Trait

| Trait | Includes |
|-------|----------|
| `HasProfile` | Addressable, Emailable, Phoneable, Websiteable, Socialable, EmergencyContactable, Credentialable, Documentable |

## Best Practices

### 1. Choose the Right Traits

Only use the traits you need:

```php
// Don't do this if you only need addresses
use HasProfile;

// Do this instead
use Addressable;
```

### 2. Consider Your Domain

Match traits to your domain requirements:

```php
// For a physical store
class Store extends Model
{
    use Addressable, Phoneable, Websiteable;
}

// For an online service
class OnlineService extends Model
{
    use Emailable, Websiteable;
}

// For employee records
class Employee extends Model
{
    use HasProfile, Bankable;
}
```

### 3. Extend When Needed

Add custom methods to your models:

```php
class User extends Model
{
    use HasProfile;

    public function getPrimaryAddress()
    {
        return $this->addresses()->where('is_primary', true)->first();
    }

    public function getDefaultEmail()
    {
        return $this->emails()->where('is_default', true)->first()->email;
    }
}
```

## What's Next?

- [Models](04-models.md) - Detailed model documentation
- [Database Schema](05-database-schema.md) - Understanding the database structure
- [Usage Guides](../03-usage/README.md) - Practical usage examples
