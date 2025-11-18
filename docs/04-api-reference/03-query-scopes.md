# Query Scopes

Available query scopes for filtering and querying profile data.

## Phone Scopes

The `Phone` model provides scopes for filtering by phone type.

### `home()`

Filter phone numbers by home type.

**Usage**:

```php
use CleaniqueCoders\Profile\Models\Phone;

// Get all home phones
$homePhones = Phone::home()->get();

// Get user's home phones
$userHomePhones = $user->phones()->home()->get();

// Get first home phone
$homePhone = $user->phones()->home()->first();
```

### `mobile()`

Filter phone numbers by mobile type.

**Usage**:

```php
// Get all mobile phones
$mobilePhones = Phone::mobile()->get();

// Get user's mobile phones
$userMobilePhones = $user->phones()->mobile()->get();

// Check if user has mobile
$hasMobile = $user->phones()->mobile()->exists();
```

### `office()`

Filter phone numbers by office type.

**Usage**:

```php
// Get all office phones
$officePhones = Phone::office()->get();

// Get user's office phones
$userOfficePhones = $user->phones()->office()->get();
```

### `other()`

Filter phone numbers by other type.

**Usage**:

```php
// Get all other type phones
$otherPhones = Phone::other()->get();

// Get user's other phones
$userOtherPhones = $user->phones()->other()->get();
```

### `fax()`

Filter phone numbers by fax type.

**Usage**:

```php
// Get all fax numbers
$faxNumbers = Phone::fax()->get();

// Get user's fax numbers
$userFaxNumbers = $user->phones()->fax()->get();
```

## Scope Chaining

Scopes can be chained with other query methods:

```php
// Get default mobile phone
$defaultMobile = $user->phones()
    ->mobile()
    ->where('is_default', true)
    ->first();

// Get all non-default office phones
$officePhones = $user->phones()
    ->office()
    ->where('is_default', false)
    ->get();

// Count mobile phones
$mobileCount = $user->phones()->mobile()->count();

// Check if has office phone
$hasOffice = $user->phones()->office()->exists();
```

## Custom Scopes

You can add custom scopes to your extended models:

### Example: Verified Scope

```php
use Illuminate\Database\Eloquent\Builder;

class Email extends \CleaniqueCoders\Profile\Models\Email
{
    /**
     * Scope for verified emails
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope for unverified emails
     */
    public function scopeUnverified(Builder $query): Builder
    {
        return $query->whereNull('verified_at');
    }
}

// Usage
$verifiedEmails = $user->emails()->verified()->get();
$unverifiedEmails = $user->emails()->unverified()->get();
```

### Example: Active Scope

```php
class Address extends \CleaniqueCoders\Profile\Models\Address
{
    /**
     * Scope for active addresses
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for addresses in country
     */
    public function scopeInCountry(Builder $query, string $countryCode): Builder
    {
        return $query->whereHas('country', function($q) use ($countryCode) {
            $q->where('code', $countryCode);
        });
    }
}

// Usage
$activeAddresses = $user->addresses()->active()->get();
$malaysianAddresses = $user->addresses()->inCountry('MY')->get();
```

### Example: Social Media Scope

```php
class Website extends \CleaniqueCoders\Profile\Models\Website
{
    /**
     * Scope for social media URLs
     */
    public function scopeSocialMedia(Builder $query): Builder
    {
        return $query->where(function($q) {
            $q->where('url', 'LIKE', '%facebook.com%')
              ->orWhere('url', 'LIKE', '%twitter.com%')
              ->orWhere('url', 'LIKE', '%instagram.com%')
              ->orWhere('url', 'LIKE', '%linkedin.com%');
        });
    }

    /**
     * Scope for corporate websites
     */
    public function scopeCorporate(Builder $query): Builder
    {
        return $query->where('url', 'NOT LIKE', '%facebook.com%')
            ->where('url', 'NOT LIKE', '%twitter.com%')
            ->where('url', 'NOT LIKE', '%instagram.com%')
            ->where('url', 'NOT LIKE', '%linkedin.com%');
    }
}

// Usage
$socialMedia = $company->websites()->socialMedia()->get();
$corporateWebsites = $company->websites()->corporate()->get();
```

## Credential Scopes

The `Credential` model provides scopes for filtering professional credentials.

### `verified()`

Filter verified credentials.

**Usage**:

```php
use CleaniqueCoders\Profile\Models\Credential;

// Get all verified credentials
$verifiedCredentials = Credential::verified()->get();

// Get user's verified credentials
$userVerified = $user->credentials()->verified()->get();
```

### `type(string $type)`

Filter credentials by specific type.

**Usage**:

```php
// Get all licenses
$licenses = $user->credentials()->type('license')->get();

// Get all certifications
$certifications = $user->credentials()->type('certification')->get();

// Get academic degrees
$degrees = $user->credentials()->type('degree')->get();
```

**Available Types**: `license`, `certification`, `diploma`, `degree`, `permit`, `accreditation`, `registration`, `membership`, `award`

### `category(string $category)`

Filter credentials by semantic category.

**Usage**:

```php
// Get all educational credentials (degrees, diplomas)
$education = $user->credentials()->category('education')->get();

// Get regulatory credentials (licenses, certifications, permits, etc.)
$regulatory = $user->credentials()->category('regulatory')->get();

// Get professional memberships
$memberships = $user->credentials()->category('association')->get();

// Get awards and recognition
$awards = $user->credentials()->category('recognition')->get();
```

**Available Categories**:

- `education` - Degrees and diplomas
- `regulatory` - Licenses, certifications, permits, accreditations, registrations
- `association` - Professional memberships
- `recognition` - Awards and honors

### `active()`

Filter active (non-expired) credentials.

**Usage**:

```php
// Get all active credentials
$active = $user->credentials()->active()->get();

// Get active regulatory credentials
$activeRegulatory = $user->credentials()
    ->category('regulatory')
    ->active()
    ->get();
```

### `expired()`

Filter expired credentials.

**Usage**:

```php
// Get expired credentials
$expired = $user->credentials()->expired()->get();

// Get expired licenses
$expiredLicenses = $user->credentials()
    ->type('license')
    ->expired()
    ->get();
```

### Credential Scope Chaining

```php
// Get verified, active regulatory credentials
$activeRegulatory = $user->credentials()
    ->category('regulatory')
    ->active()
    ->verified()
    ->get();

// Get expiring licenses (within 90 days)
$expiringLicenses = $user->credentials()
    ->type('license')
    ->whereNotNull('expires_at')
    ->where('expires_at', '<=', Carbon::now()->addDays(90))
    ->where('expires_at', '>', Carbon::now())
    ->get();

// Get unverified educational credentials
$unverifiedEducation = $user->credentials()
    ->category('education')
    ->where('is_verified', false)
    ->get();
```

## Scope Summary

| Model | Scope | Description |
|-------|-------|-------------|
| Phone | `home()` | Filter home phones |
| Phone | `mobile()` | Filter mobile phones |
| Phone | `office()` | Filter office phones |
| Phone | `other()` | Filter other type phones |
| Phone | `fax()` | Filter fax numbers |
| Credential | `verified()` | Filter verified credentials |
| Credential | `type(string $type)` | Filter by credential type |
| Credential | `category(string $category)` | Filter by credential category |
| Credential | `active()` | Filter active (non-expired) credentials |
| Credential | `expired()` | Filter expired credentials |
| Document | `verified()` | Filter verified documents |
| Document | `type(string $type)` | Filter by document type |
| Document | `active()` | Filter active (non-expired) documents |
| Document | `expired()` | Filter expired documents |

## Best Practices

### 1. Use Scopes for Readability

```php
// ❌ Bad: Hardcoded type IDs
$mobilePhones = $user->phones()->where('phone_type_id', 2)->get();

// ✅ Good: Use scopes
$mobilePhones = $user->phones()->mobile()->get();
```

### 2. Chain Scopes Logically

```php
// Get default mobile phone
$phone = $user->phones()
    ->mobile()
    ->where('is_default', true)
    ->first();
```

### 3. Create Reusable Scopes

```php
// Define once, use everywhere
public function scopeVerified(Builder $query): Builder
{
    return $query->whereNotNull('verified_at');
}

// Use across the application
$verifiedEmails = Email::verified()->get();
$userVerifiedEmails = $user->emails()->verified()->get();
```

### 4. Document Custom Scopes

```php
/**
 * Scope to get expired records
 *
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeExpired(Builder $query): Builder
{
    return $query->where('expires_at', '<', now());
}
```
