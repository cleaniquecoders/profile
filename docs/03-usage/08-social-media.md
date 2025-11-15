# Working with Social Media Profiles

Social media profiles allow you to store links to a user's social media accounts across various platforms like LinkedIn, GitHub, Twitter, Instagram, and more.

## Overview

The `Socialable` trait provides methods for managing social media accounts associated with any model. Each social media account can store the platform name, username, URL, and verification status.

## Setup

Add the `Socialable` trait to your model, or use the `HasProfile` trait which includes it:

```php
use CleaniqueCoders\Profile\Concerns\Socialable;
// OR
use CleaniqueCoders\Profile\Concerns\HasProfile;

class User extends Model
{
    use HasProfile; // Includes Socialable and other profile traits
}
```

## Creating Social Media Accounts

### Basic Creation

```php
$user = User::find(1);

$user->socialMedia()->create([
    'platform' => 'linkedin',
    'username' => 'johndoe',
    'url' => 'https://linkedin.com/in/johndoe',
    'is_verified' => true,
    'is_primary' => true,
]);
```

### Adding Multiple Platforms

```php
$user->socialMedia()->createMany([
    [
        'platform' => 'github',
        'username' => 'johndoe',
        'url' => 'https://github.com/johndoe',
        'is_verified' => true,
    ],
    [
        'platform' => 'twitter',
        'username' => 'johndoe',
        'url' => 'https://twitter.com/johndoe',
        'is_verified' => false,
    ],
    [
        'platform' => 'linkedin',
        'username' => 'john-doe',
        'url' => 'https://linkedin.com/in/john-doe',
        'is_verified' => true,
        'is_primary' => true,
    ],
]);
```

## Retrieving Social Media Accounts

### Get All Accounts

```php
$socialAccounts = $user->socialMedia;

foreach ($socialAccounts as $account) {
    echo "{$account->platform}: {$account->url}";
}
```

### Get Primary Account

```php
$primaryAccount = $user->primarySocialMedia();

if ($primaryAccount) {
    echo "Primary social: {$primaryAccount->url}";
}
```

### Get Account by Platform

```php
$linkedin = $user->getSocialMediaByPlatform('linkedin');

if ($linkedin) {
    echo "LinkedIn: {$linkedin->url}";
}
```

### Check if Platform Exists

```php
// Check if user has any social media
if ($user->hasSocialMedia()) {
    echo 'User has social media accounts';
}

// Check if user has specific platform
if ($user->hasSocialMedia('github')) {
    echo 'User has a GitHub account';
}
```

## Updating Social Media Accounts

```php
$account = $user->socialMedia()->where('platform', 'github')->first();

$account->update([
    'username' => 'newusername',
    'url' => 'https://github.com/newusername',
    'is_verified' => true,
]);
```

## Query Scopes

### Get Verified Accounts

```php
$verifiedAccounts = $user->socialMedia()->verified()->get();
```

### Filter by Platform

```php
$githubAccount = $user->socialMedia()->platform('github')->first();
```

### Get Primary Account

```php
$primary = $user->socialMedia()->primary()->first();
```

### Combining Scopes

```php
$verifiedLinkedIn = $user->socialMedia()
    ->verified()
    ->platform('linkedin')
    ->first();
```

## Deleting Social Media Accounts

```php
// Delete specific account
$account = $user->socialMedia()->where('platform', 'twitter')->first();
$account->delete();

// Delete all accounts
$user->socialMedia()->delete();

// Soft delete is enabled by default
// To permanently delete:
$account->forceDelete();
```

## Supported Platforms

The package supports any platform name, but here are common ones:

| Platform | Key | Example URL |
|----------|-----|-------------|
| Facebook | `facebook` | https://facebook.com/username |
| Twitter/X | `twitter` | https://twitter.com/username |
| Instagram | `instagram` | https://instagram.com/username |
| LinkedIn | `linkedin` | https://linkedin.com/in/username |
| GitHub | `github` | https://github.com/username |
| GitLab | `gitlab` | https://gitlab.com/username |
| YouTube | `youtube` | https://youtube.com/@username |
| TikTok | `tiktok` | https://tiktok.com/@username |
| Pinterest | `pinterest` | https://pinterest.com/username |
| Snapchat | `snapchat` | https://snapchat.com/add/username |
| Reddit | `reddit` | https://reddit.com/u/username |
| Telegram | `telegram` | https://t.me/username |
| Discord | `discord` | username#1234 |
| Medium | `medium` | https://medium.com/@username |
| Behance | `behance` | https://behance.net/username |
| Dribbble | `dribbble` | https://dribbble.com/username |
| Stack Overflow | `stackoverflow` | https://stackoverflow.com/users/id/username |
| Twitch | `twitch` | https://twitch.tv/username |

## Complete Example

```php
use App\Models\User;

// Create a new user with social media profiles
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// Add social media accounts
$user->socialMedia()->createMany([
    [
        'platform' => 'linkedin',
        'username' => 'johndoe',
        'url' => 'https://linkedin.com/in/johndoe',
        'is_verified' => true,
        'is_primary' => true,
    ],
    [
        'platform' => 'github',
        'username' => 'johndoe',
        'url' => 'https://github.com/johndoe',
        'is_verified' => true,
    ],
    [
        'platform' => 'twitter',
        'username' => 'johndoe',
        'url' => 'https://twitter.com/johndoe',
        'is_verified' => false,
    ],
]);

// Display all social media
foreach ($user->socialMedia as $account) {
    echo sprintf(
        "%s: %s %s\n",
        ucfirst($account->platform),
        $account->url,
        $account->is_verified ? '✓' : ''
    );
}

// Get primary social media
$primary = $user->primarySocialMedia();
echo "Primary: {$primary->platform} - {$primary->url}\n";

// Check for specific platform
if ($user->hasSocialMedia('github')) {
    $github = $user->getSocialMediaByPlatform('github');
    echo "GitHub profile: {$github->url}\n";
}
```

## Best Practices

### Validation

Always validate social media data:

```php
$validated = $request->validate([
    'platform' => 'required|string|max:50',
    'username' => 'nullable|string|max:255',
    'url' => 'required|url|max:500',
    'is_verified' => 'boolean',
    'is_primary' => 'boolean',
]);

$user->socialMedia()->create($validated);
```

### URL Standardization

Standardize URLs before saving:

```php
$url = $request->input('url');
$platform = $request->input('platform');

// Add protocol if missing
if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
    $url = "https://{$url}";
}

$user->socialMedia()->create([
    'platform' => $platform,
    'url' => $url,
    'is_verified' => false,
]);
```

### Primary Account Management

Ensure only one primary account:

```php
// When setting a new primary, unset others
DB::transaction(function () use ($user, $platformId) {
    // Remove primary flag from all accounts
    $user->socialMedia()->update(['is_primary' => false]);

    // Set new primary
    $user->socialMedia()->find($platformId)->update(['is_primary' => true]);
});
```

## Related Documentation

- [Working with Websites](04-websites.md) - Similar concept for website URLs
- [Advanced Queries](06-advanced-queries.md) - Complex query patterns
- [Best Practices](07-best-practices.md) - General recommendations
