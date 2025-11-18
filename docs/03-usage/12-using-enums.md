# Using Enums

The Profile package provides type-safe enums for various profile data types using the `InteractsWithEnum` trait from the Traitify package.

## Available Enums

### PhoneType

Located at: `CleaniqueCoders\Profile\Enums\PhoneType`

**Values:**

- `HOME` - Home phone number
- `MOBILE` - Mobile/cell phone number
- `OFFICE` - Office/work phone number
- `FAX` - Fax number
- `OTHER` - Other phone number type

**Usage:**

```php
use CleaniqueCoders\Profile\Enums\PhoneType;

// Using enum value
$user->phones()->create([
    'phone_number' => '+60123456789',
    'phone_type' => PhoneType::MOBILE,
    'is_default' => true,
]);

// Get all values
$phoneTypes = PhoneType::values(); // ['home', 'mobile', 'office', 'fax', 'other']

// Get all labels
$labels = PhoneType::labels(); // ['Home', 'Mobile', 'Office', 'Fax', 'Other']

// Get options for select inputs
$options = PhoneType::options();
// [
//     ['value' => 'home', 'label' => 'Home', 'description' => 'Home phone number'],
//     ['value' => 'mobile', 'label' => 'Mobile', 'description' => 'Mobile/cell phone number'],
//     ...
// ]

// Get label
echo PhoneType::MOBILE->label(); // "Mobile"

// Get description
echo PhoneType::MOBILE->description(); // "Mobile/cell phone number"
```

### SocialMediaPlatform

Located at: `CleaniqueCoders\Profile\Enums\SocialMediaPlatform`

**Values:**

- `FACEBOOK`, `TWITTER`, `INSTAGRAM`, `LINKEDIN`, `GITHUB`, `GITLAB`, `YOUTUBE`, `TIKTOK`, `PINTEREST`, `SNAPCHAT`, `REDDIT`, `TELEGRAM`, `WHATSAPP`, `DISCORD`, `SLACK`, `MEDIUM`, `BEHANCE`, `DRIBBBLE`, `STACKOVERFLOW`, `TWITCH`

**Usage:**

```php
use CleaniqueCoders\Profile\Enums\SocialMediaPlatform;

// Using enum value
$user->socialMedia()->create([
    'platform' => SocialMediaPlatform::LINKEDIN,
    'username' => 'johndoe',
    'url' => 'https://linkedin.com/in/johndoe',
    'is_verified' => true,
]);

// Get URL pattern
$pattern = SocialMediaPlatform::LINKEDIN->urlPattern();
// "https://linkedin.com/in/{username}"

// Get label
echo SocialMediaPlatform::GITHUB->label(); // "GitHub"

// Get all platform values
$platforms = SocialMediaPlatform::values();
```

### RelationshipType

Located at: `CleaniqueCoders\Profile\Enums\RelationshipType`

**Values:**

- `SPOUSE`, `PARTNER`, `PARENT`, `FATHER`, `MOTHER`, `SIBLING`, `BROTHER`, `SISTER`, `CHILD`, `SON`, `DAUGHTER`, `GRANDPARENT`, `GRANDCHILD`, `FRIEND`, `COLLEAGUE`, `NEIGHBOR`, `GUARDIAN`, `OTHER`

**Usage:**

```php
use CleaniqueCoders\Profile\Enums\RelationshipType;

// Using enum value
$user->emergencyContacts()->create([
    'name' => 'Jane Doe',
    'relationship_type' => RelationshipType::SPOUSE,
    'phone' => '+1234567890',
    'email' => 'jane@example.com',
    'is_primary' => true,
]);

// Check if family relationship
if (RelationshipType::SPOUSE->isFamily()) {
    // This is a family member
}

// Get label
echo RelationshipType::SPOUSE->label(); // "Spouse"

// Get all relationship types
$types = RelationshipType::values();
```

### CredentialType

Located at: `CleaniqueCoders\Profile\Enums\CredentialType`

**Values:**

- `LICENSE` - Professional license
- `CERTIFICATION` - Professional certification
- `DIPLOMA` - Educational diploma
- `DEGREE` - Academic degree
- `PERMIT` - Work or professional permit
- `ACCREDITATION` - Professional accreditation
- `REGISTRATION` - Professional registration
- `MEMBERSHIP` - Professional membership
- `AWARD` - Professional award or recognition

**Usage:**

```php
use CleaniqueCoders\Profile\Enums\CredentialType;

// Using enum value
$user->credentials()->create([
    'type' => CredentialType::LICENSE,
    'title' => 'Medical License',
    'issuer' => 'State Medical Board',
    'number' => 'ML-123456',
    'issued_at' => '2020-01-01',
    'expires_at' => '2025-12-31',
    'is_verified' => true,
]);

// Check if typically expires
if (CredentialType::LICENSE->typicallyExpires()) {
    // This credential type typically has an expiration date
}

// Get category
echo CredentialType::LICENSE->category(); // "regulatory"
echo CredentialType::DEGREE->category(); // "education"
echo CredentialType::MEMBERSHIP->category(); // "association"
echo CredentialType::AWARD->category(); // "recognition"

// Get label
echo CredentialType::LICENSE->label(); // "License"

// Get all credential types
$types = CredentialType::values();
```

**Categories:**

Credential types are organized into semantic categories:

- **education**: `DEGREE`, `DIPLOMA`
- **regulatory**: `LICENSE`, `CERTIFICATION`, `PERMIT`, `ACCREDITATION`, `REGISTRATION`
- **association**: `MEMBERSHIP`
- **recognition**: `AWARD`

```php
// Filter credentials by category
$educationCredentials = $user->credentials()
    ->category('education')
    ->get();

$regulatoryCredentials = $user->credentials()
    ->category('regulatory')
    ->active()
    ->get();
```

### DocumentType

Located at: `CleaniqueCoders\Profile\Enums\DocumentType`

**Values:**

- `PASSPORT` - Passport
- `ID` - National ID Card
- `DRIVER_LICENSE` - Driver's License
- `VISA` - Visa
- `WORK_PERMIT` - Work Permit
- `CERTIFICATE` - Certificate
- `DIPLOMA` - Diploma
- `CONTRACT` - Contract
- `AGREEMENT` - Agreement
- `RESUME` - Resume/CV
- `TAX_DOCUMENT` - Tax Document
- `INSURANCE` - Insurance Policy
- `MEDICAL_RECORD` - Medical Record
- `OTHER` - Other Document

**Usage:**

```php
use CleaniqueCoders\Profile\Enums\DocumentType;

// Using enum value
$user->documents()->create([
    'type' => DocumentType::PASSPORT,
    'title' => 'US Passport',
    'file_path' => 'documents/passport.pdf',
    'file_type' => 'pdf',
    'issued_at' => '2020-01-01',
    'expires_at' => '2030-01-01',
    'is_verified' => true,
]);

// Check if typically expires
if (DocumentType::PASSPORT->typicallyExpires()) {
    // This document type typically has an expiration date
}

// Get typical file extensions
$extensions = DocumentType::PASSPORT->typicalExtensions();
// ['pdf', 'jpg', 'jpeg', 'png']

// Get label
echo DocumentType::PASSPORT->label(); // "Passport"

// Get all document types
$types = DocumentType::values();
```

## Form Validation with Enums

### Using Enum Values in Validation Rules

```php
use CleaniqueCoders\Profile\Enums\PhoneType;
use CleaniqueCoders\Profile\Enums\SocialMediaPlatform;
use CleaniqueCoders\Profile\Enums\RelationshipType;
use Illuminate\Validation\Rules\Enum;

// Validate enum values
$request->validate([
    'phone_type' => ['required', new Enum(PhoneType::class)],
    'platform' => ['required', new Enum(SocialMediaPlatform::class)],
    'relationship_type' => ['required', new Enum(RelationshipType::class)],
]);

// Or using Rule::enum() in Laravel 10+
use Illuminate\Validation\Rule;

$request->validate([
    'phone_type' => ['required', Rule::enum(PhoneType::class)],
]);
```

### Generating Select Options

```php
use CleaniqueCoders\Profile\Enums\PhoneType;

// In your controller
public function create()
{
    return view('phones.create', [
        'phoneTypes' => PhoneType::options(),
    ]);
}

// In your Blade view
<select name="phone_type">
    @foreach($phoneTypes as $type)
        <option value="{{ $type['value'] }}"
                title="{{ $type['description'] }}">
            {{ $type['label'] }}
        </option>
    @endforeach
</select>
```

## Querying with Enums

```php
use CleaniqueCoders\Profile\Enums\PhoneType;
use CleaniqueCoders\Profile\Enums\SocialMediaPlatform;

// Query with enum values
$mobilePhones = $user->phones()
    ->where('phone_type', PhoneType::MOBILE)
    ->get();

$linkedInProfile = $user->socialMedia()
    ->where('platform', SocialMediaPlatform::LINKEDIN)
    ->first();

// The model will automatically cast to/from enum
foreach ($user->phones as $phone) {
    echo $phone->phone_type->label(); // Returns "Mobile", "Home", etc.
}
```

## Configuration

Enums are registered in the configuration file:

```php
// config/profile.php
'enums' => [
    'phone_type' => PhoneType::class,
    'social_media_platform' => SocialMediaPlatform::class,
    'relationship_type' => RelationshipType::class,
    'credential_type' => CredentialType::class,
    'document_type' => DocumentType::class,
],
```

## Benefits of Using Enums

1. **Type Safety** - Prevents invalid values at the PHP level
2. **Auto-completion** - IDEs can provide autocomplete suggestions
3. **Refactoring** - Easier to refactor when values change
4. **Documentation** - Built-in labels and descriptions
5. **Validation** - Built-in Laravel validation support
6. **Consistency** - Ensures consistent values across the application

## Related Documentation

- [Working with Phones](03-usage/03-phones.md)
- [Working with Social Media](03-usage/08-social-media.md)
- [Working with Emergency Contacts](03-usage/09-emergency-contacts.md)
- [Working with Credentials](03-usage/10-credentials.md)
- [Working with Documents](03-usage/11-documents.md)
