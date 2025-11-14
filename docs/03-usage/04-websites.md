# Working with Websites

Learn how to manage website URLs using the `Websiteable` trait.

## Setup

Add the `Websiteable` trait to your model:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\Websiteable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Websiteable;
}
```

## Creating Websites

### Basic Website Creation

```php
$company = Company::find(1);

$website = $company->websites()->create([
    'url' => 'https://example.com',
    'is_default' => true,
]);
```

### Creating Multiple Websites

```php
// Main website
$company->websites()->create([
    'url' => 'https://example.com',
    'is_default' => true,
]);

// Shop website
$company->websites()->create([
    'url' => 'https://shop.example.com',
    'is_default' => false,
]);

// Blog
$company->websites()->create([
    'url' => 'https://blog.example.com',
    'is_default' => false,
]);

// Support portal
$company->websites()->create([
    'url' => 'https://support.example.com',
    'is_default' => false,
]);
```

### Social Media Profiles

```php
// LinkedIn
$company->websites()->create([
    'url' => 'https://linkedin.com/company/example',
    'is_default' => false,
]);

// Twitter/X
$company->websites()->create([
    'url' => 'https://twitter.com/example',
    'is_default' => false,
]);

// Facebook
$company->websites()->create([
    'url' => 'https://facebook.com/example',
    'is_default' => false,
]);

// Instagram
$company->websites()->create([
    'url' => 'https://instagram.com/example',
    'is_default' => false,
]);
```

## Retrieving Websites

### Get All Websites

```php
$websites = $company->websites;

foreach ($websites as $website) {
    echo $website->url;
}
```

### Get Default Website

```php
$defaultWebsite = $company->websites()
    ->where('is_default', true)
    ->first();

$mainUrl = $defaultWebsite?->url;
```

### Filter by Domain

```php
// Get all subdomains
$subdomains = $company->websites()
    ->where('url', 'LIKE', '%.example.com%')
    ->get();

// Get social media links
$socialMedia = $company->websites()
    ->where(function($query) {
        $query->where('url', 'LIKE', '%facebook.com%')
              ->orWhere('url', 'LIKE', '%twitter.com%')
              ->orWhere('url', 'LIKE', '%instagram.com%')
              ->orWhere('url', 'LIKE', '%linkedin.com%');
    })
    ->get();
```

## Updating Websites

### Update URL

```php
$website = $company->websites()->first();

$website->update([
    'url' => 'https://newdomain.com',
]);
```

### Set as Default

```php
// Remove default flag from all websites
$company->websites()->update(['is_default' => false]);

// Set specific website as default
$website = $company->websites()
    ->where('url', 'https://example.com')
    ->first();

$website->update(['is_default' => true]);
```

## Deleting Websites

### Soft Delete

```php
$website = $company->websites()->first();
$website->delete();
```

### Restore

```php
$website = $company->websites()->withTrashed()->find($websiteId);
$website->restore();
```

### Permanent Delete

```php
$website->forceDelete();
```

## Advanced Patterns

### Helper Methods

```php
class Company extends Model
{
    use Websiteable;

    /**
     * Get the main website URL
     */
    public function getMainWebsite(): ?string
    {
        return $this->websites()
            ->where('is_default', true)
            ->first()
            ?->url;
    }

    /**
     * Get all website URLs as array
     */
    public function getWebsiteUrls(): array
    {
        return $this->websites()
            ->pluck('url')
            ->toArray();
    }

    /**
     * Get social media links
     */
    public function getSocialMediaLinks(): array
    {
        $platforms = [
            'facebook' => 'facebook.com',
            'twitter' => 'twitter.com',
            'x' => 'x.com',
            'instagram' => 'instagram.com',
            'linkedin' => 'linkedin.com',
            'youtube' => 'youtube.com',
            'tiktok' => 'tiktok.com',
        ];

        $links = [];

        foreach ($platforms as $platform => $domain) {
            $website = $this->websites()
                ->where('url', 'LIKE', "%{$domain}%")
                ->first();

            if ($website) {
                $links[$platform] = $website->url;
            }
        }

        return $links;
    }

    /**
     * Get shop URL
     */
    public function getShopUrl(): ?string
    {
        return $this->websites()
            ->where('url', 'LIKE', '%shop%')
            ->orWhere('url', 'LIKE', '%store%')
            ->first()
            ?->url;
    }
}
```

### Validation

```php
use Illuminate\Http\Request;

public function storeWebsite(Request $request, Company $company)
{
    $validated = $request->validate([
        'url' => [
            'required',
            'url',
            'max:255',
            // Ensure URL is unique for this company
            'unique:websites,url,NULL,id,websiteable_type,' . get_class($company) . ',websiteable_id,' . $company->id,
        ],
        'is_default' => 'boolean',
    ]);

    // Ensure URL has protocol
    if (!str_starts_with($validated['url'], 'http')) {
        $validated['url'] = 'https://' . $validated['url'];
    }

    // If setting as default, remove default from others
    if ($validated['is_default'] ?? false) {
        $company->websites()->update(['is_default' => false]);
    }

    return $company->websites()->create($validated);
}
```

### URL Normalization

```php
class Website extends \CleaniqueCoders\Profile\Models\Website
{
    /**
     * Set the URL attribute
     */
    public function setUrlAttribute($value)
    {
        // Add https:// if no protocol
        if (!preg_match('/^https?:\/\//', $value)) {
            $value = 'https://' . $value;
        }

        // Remove trailing slash
        $value = rtrim($value, '/');

        $this->attributes['url'] = $value;
    }

    /**
     * Get the domain name
     */
    public function getDomainAttribute(): string
    {
        return parse_url($this->url, PHP_URL_HOST);
    }

    /**
     * Get clickable link HTML
     */
    public function getLinkHtmlAttribute(): string
    {
        $domain = $this->domain;
        return "<a href=\"{$this->url}\" target=\"_blank\" rel=\"noopener\">{$domain}</a>";
    }

    /**
     * Check if URL is social media
     */
    public function isSocialMedia(): bool
    {
        $socialDomains = [
            'facebook.com', 'twitter.com', 'x.com', 'instagram.com',
            'linkedin.com', 'youtube.com', 'tiktok.com', 'pinterest.com',
        ];

        foreach ($socialDomains as $domain) {
            if (str_contains($this->url, $domain)) {
                return true;
            }
        }

        return false;
    }
}
```

## Common Use Cases

### Corporate Website Portfolio

```php
// Add type column via migration
Schema::table('websites', function (Blueprint $table) {
    $table->string('type')->nullable(); // 'main', 'shop', 'blog', 'support'
    $table->string('label')->nullable(); // Custom label
});

// Usage
$company->websites()->create([
    'url' => 'https://example.com',
    'type' => 'main',
    'label' => 'Corporate Website',
    'is_default' => true,
]);

$company->websites()->create([
    'url' => 'https://shop.example.com',
    'type' => 'shop',
    'label' => 'Online Store',
]);

// Retrieve by type
$shopUrl = $company->websites()
    ->where('type', 'shop')
    ->first()
    ?->url;
```

### Portfolio Links for Developers

```php
class Developer extends Model
{
    use Websiteable;

    /**
     * Get GitHub profile
     */
    public function getGithubUrl(): ?string
    {
        return $this->websites()
            ->where('url', 'LIKE', '%github.com%')
            ->first()
            ?->url;
    }

    /**
     * Get portfolio website
     */
    public function getPortfolioUrl(): ?string
    {
        return $this->websites()
            ->where('is_default', true)
            ->first()
            ?->url;
    }

    /**
     * Add repository link
     */
    public function addRepository(string $repoUrl): void
    {
        $this->websites()->create([
            'url' => $repoUrl,
            'type' => 'repository',
            'is_default' => false,
        ]);
    }
}
```

### Website Health Monitoring

```php
// Add columns via migration
Schema::table('websites', function (Blueprint $table) {
    $table->boolean('is_active')->default(true);
    $table->timestamp('last_checked_at')->nullable();
    $table->integer('http_status')->nullable();
});

// Extended model
class Website extends \CleaniqueCoders\Profile\Models\Website
{
    /**
     * Check if website is online
     */
    public function checkStatus(): bool
    {
        try {
            $response = Http::timeout(10)->get($this->url);

            $this->update([
                'http_status' => $response->status(),
                'is_active' => $response->ok(),
                'last_checked_at' => now(),
            ]);

            return $response->ok();
        } catch (\Exception $e) {
            $this->update([
                'is_active' => false,
                'last_checked_at' => now(),
            ]);

            return false;
        }
    }
}

// Usage
$website = $company->websites()->first();
$isOnline = $website->checkStatus();
```

### Open Graph Metadata

```php
class Website extends \CleaniqueCoders\Profile\Models\Website
{
    /**
     * Get Open Graph metadata
     */
    public function getMetadata(): array
    {
        try {
            $html = Http::get($this->url)->body();

            $doc = new \DOMDocument();
            @$doc->loadHTML($html);

            $metadata = [];

            $metas = $doc->getElementsByTagName('meta');

            foreach ($metas as $meta) {
                $property = $meta->getAttribute('property');

                if (str_starts_with($property, 'og:')) {
                    $key = str_replace('og:', '', $property);
                    $metadata[$key] = $meta->getAttribute('content');
                }
            }

            return $metadata;
        } catch (\Exception $e) {
            return [];
        }
    }
}

// Usage
$website = $company->websites()->first();
$metadata = $website->getMetadata();
// ['title' => '...', 'description' => '...', 'image' => '...']
```

### QR Code Generation

```php
class Website extends \CleaniqueCoders\Profile\Models\Website
{
    /**
     * Get QR code for URL
     */
    public function getQrCodeUrl(): string
    {
        // Using a QR code service
        $encodedUrl = urlencode($this->url);
        return "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$encodedUrl}";
    }

    /**
     * Generate QR code attribute
     */
    public function getQrCodeAttribute(): string
    {
        return $this->getQrCodeUrl();
    }
}

// Usage
$website = $company->websites()->first();
echo "<img src=\"{$website->qr_code}\" alt=\"QR Code\" />";
```

## What's Next?

- [Working with Bank Accounts](05-bank-accounts.md) - Managing banking information
- [Advanced Queries](06-advanced-queries.md) - Complex query examples
- [Best Practices](07-best-practices.md) - Tips and recommendations
