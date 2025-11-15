# Working with Documents

Documents allow you to store file-based credentials and important paperwork such as passports, IDs, certificates, contracts, and other official documents, along with their metadata and expiration tracking.

## Overview

The `Documentable` trait provides methods for managing document attachments associated with any model. Each document includes type, title, file path, issue and expiration dates, and verification status.

## Setup

Add the `Documentable` trait to your model, or use the `HasProfile` trait which includes it:

```php
use CleaniqueCoders\Profile\Concerns\Documentable;
// OR
use CleaniqueCoders\Profile\Concerns\HasProfile;

class User extends Model
{
    use HasProfile; // Includes Documentable and other profile traits
}
```

## Creating Documents

### Basic Creation

```php
$user = User::find(1);

$user->documents()->create([
    'type' => 'passport',
    'title' => 'US Passport',
    'file_path' => 'documents/passport_scan.pdf',
    'file_type' => 'pdf',
    'file_size' => 2048576, // in bytes
    'issued_at' => '2020-01-01',
    'expires_at' => '2030-01-01',
    'is_verified' => true,
    'notes' => 'Primary identification document',
]);
```

### With File Upload

```php
$file = $request->file('document');

$path = $file->store('documents', 'private');

$user->documents()->create([
    'type' => 'id',
    'title' => 'National ID Card',
    'file_path' => $path,
    'file_type' => $file->extension(),
    'file_size' => $file->getSize(),
    'issued_at' => $request->issued_at,
    'expires_at' => $request->expires_at,
    'is_verified' => false,
]);
```

### Adding Multiple Documents

```php
$documents = [
    [
        'type' => 'certificate',
        'title' => 'University Degree',
        'file_path' => 'documents/degree.pdf',
        'file_type' => 'pdf',
        'file_size' => 1024000,
        'issued_at' => '2019-05-20',
        'is_verified' => true,
    ],
    [
        'type' => 'certificate',
        'title' => 'AWS Certification',
        'file_path' => 'documents/aws_cert.pdf',
        'file_type' => 'pdf',
        'file_size' => 512000,
        'issued_at' => '2023-06-15',
        'expires_at' => '2026-06-15',
        'is_verified' => true,
    ],
];

$user->documents()->createMany($documents);
```

## Retrieving Documents

### Get All Documents

```php
$documents = $user->documents;

foreach ($documents as $document) {
    echo "{$document->title} ({$document->type})";
}
```

### Get Active Documents

```php
$activeDocuments = $user->activeDocuments();

foreach ($activeDocuments as $document) {
    $expires = $document->expires_at ? "Expires: {$document->expires_at->format('Y-m-d')}" : 'No expiration';
    echo "{$document->title} - {$expires}";
}
```

### Get Expired Documents

```php
$expiredDocuments = $user->expiredDocuments();

foreach ($expiredDocuments as $document) {
    echo "{$document->title} - Expired: {$document->expires_at->format('Y-m-d')}";
}
```

### Get Documents by Type

```php
$passports = $user->getDocumentsByType('passport');
$certificates = $user->getDocumentsByType('certificate');
$contracts = $user->getDocumentsByType('contract');
```

### Check Document Status

```php
// Check if has any active documents
if ($user->hasActiveDocuments()) {
    echo 'User has active documents';
}

// Check if has specific document type
if ($user->hasDocument('passport')) {
    echo 'User has passport on file';
}
```

## Downloading Documents

### Get File Path

```php
$document = $user->documents()->first();

// Get storage path
$path = Storage::disk('private')->path($document->file_path);

// Download response
return Storage::disk('private')->download(
    $document->file_path,
    $document->title . '.' . $document->file_type
);
```

### Stream Document

```php
$document = $user->documents()->find($id);

return response()->streamDownload(function () use ($document) {
    echo Storage::disk('private')->get($document->file_path);
}, $document->title . '.' . $document->file_type);
```

## Updating Documents

```php
$document = $user->documents()->first();

$document->update([
    'is_verified' => true,
    'notes' => 'Verified by admin on ' . now()->format('Y-m-d'),
]);
```

### Replace Document File

```php
$document = $user->documents()->find($id);

// Delete old file
Storage::disk('private')->delete($document->file_path);

// Store new file
$newFile = $request->file('document');
$newPath = $newFile->store('documents', 'private');

// Update document record
$document->update([
    'file_path' => $newPath,
    'file_type' => $newFile->extension(),
    'file_size' => $newFile->getSize(),
    'is_verified' => false,
]);
```

## Query Scopes

### Get Verified Documents

```php
$verified = $user->documents()->verified()->get();
```

### Filter by Type

```php
$ids = $user->documents()->type('id')->get();
$passports = $user->documents()->type('passport')->get();
```

### Get Active Documents (Scope)

```php
$active = $user->documents()->active()->get();
```

### Get Expired Documents (Scope)

```php
$expired = $user->documents()->expired()->get();
```

### Combining Scopes

```php
$activePassports = $user->documents()
    ->type('passport')
    ->active()
    ->verified()
    ->get();
```

## Deleting Documents

```php
// Delete document record and file
$document = $user->documents()->first();

// Delete file from storage
Storage::disk('private')->delete($document->file_path);

// Delete database record
$document->delete();

// Or use a model event to auto-delete file
// (see Best Practices section)
```

## Document Types

Common document types include:

| Type | Description | Typical Expiration |
|------|-------------|-------------------|
| `passport` | Passport | Yes |
| `id` | National ID card | Yes |
| `driver_license` | Driver's license | Yes |
| `visa` | Travel visa | Yes |
| `work_permit` | Work authorization | Yes |
| `certificate` | Certificate or diploma | No |
| `diploma` | Educational diploma | No |
| `contract` | Employment or service contract | Yes |
| `agreement` | Legal agreement | Varies |
| `resume` | Resume or CV | No |
| `tax_document` | Tax forms | No |
| `insurance` | Insurance policy | Yes |
| `medical_record` | Medical records | No |

## Complete Example

```php
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

// Upload and store user documents
$user = User::find(1);

// Upload passport
$passportFile = $request->file('passport');
$passportPath = $passportFile->store('documents/passports', 'private');

$passport = $user->documents()->create([
    'type' => 'passport',
    'title' => 'US Passport',
    'file_path' => $passportPath,
    'file_type' => $passportFile->extension(),
    'file_size' => $passportFile->getSize(),
    'issued_at' => '2020-01-01',
    'expires_at' => '2030-01-01',
    'is_verified' => false,
    'notes' => 'Pending verification',
]);

// Upload driver's license
$licenseFile = $request->file('license');
$licensePath = $licenseFile->store('documents/licenses', 'private');

$license = $user->documents()->create([
    'type' => 'driver_license',
    'title' => 'Driver License',
    'file_path' => $licensePath,
    'file_type' => $licenseFile->extension(),
    'file_size' => $licenseFile->getSize(),
    'issued_at' => '2019-06-15',
    'expires_at' => '2025-06-15',
    'is_verified' => false,
]);

// Display all documents
echo "Documents for {$user->name}:\n\n";
foreach ($user->documents as $document) {
    $status = $document->is_verified ? '✓ Verified' : '⏳ Pending';
    $expiry = $document->expires_at
        ? " | Expires: {$document->expires_at->format('M d, Y')}"
        : '';

    $size = number_format($document->file_size / 1024, 2);

    echo "{$document->title}\n";
    echo "  Type: {$document->type} | Format: {$document->file_type} | Size: {$size} KB\n";
    echo "  Status: {$status}{$expiry}\n\n";
}

// Check for expiring documents
$expiringDocuments = $user->documents()
    ->whereNotNull('expires_at')
    ->where('expires_at', '<=', Carbon::now()->addDays(90))
    ->where('expires_at', '>', Carbon::now())
    ->get();

if ($expiringDocuments->count() > 0) {
    echo "⚠️ Documents expiring soon:\n";
    foreach ($expiringDocuments as $document) {
        $daysLeft = Carbon::now()->diffInDays($document->expires_at);
        echo "- {$document->title} expires in {$daysLeft} days\n";
    }
}

// Download a document
$document = $user->documents()->where('type', 'passport')->first();
return Storage::disk('private')->download(
    $document->file_path,
    $document->title . '.' . $document->file_type
);
```

## Best Practices

### Validation

Always validate document uploads:

```php
$validated = $request->validate([
    'type' => 'required|string|in:passport,id,driver_license,certificate,contract',
    'title' => 'required|string|max:255',
    'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
    'issued_at' => 'required|date',
    'expires_at' => 'nullable|date|after:issued_at',
    'notes' => 'nullable|string|max:1000',
]);

$file = $request->file('document');
$path = $file->store('documents', 'private');

$user->documents()->create([
    'type' => $validated['type'],
    'title' => $validated['title'],
    'file_path' => $path,
    'file_type' => $file->extension(),
    'file_size' => $file->getSize(),
    'issued_at' => $validated['issued_at'],
    'expires_at' => $validated['expires_at'] ?? null,
    'notes' => $validated['notes'] ?? null,
    'is_verified' => false,
]);
```

### Auto-delete Files

Use model events to automatically delete files:

```php
// In your Document model
protected static function booted()
{
    static::deleting(function ($document) {
        if ($document->file_path) {
            Storage::disk('private')->delete($document->file_path);
        }
    });

    static::updating(function ($document) {
        // Delete old file if file_path changed
        if ($document->isDirty('file_path') && $document->getOriginal('file_path')) {
            Storage::disk('private')->delete($document->getOriginal('file_path'));
        }
    });
}
```

### Secure File Access

Implement authorization for document access:

```php
// In your controller
public function download(Document $document)
{
    $this->authorize('view', $document);

    return Storage::disk('private')->download(
        $document->file_path,
        $document->title . '.' . $document->file_type
    );
}

// In your DocumentPolicy
public function view(User $user, Document $document): bool
{
    return $user->id === $document->documentable_id
        || $user->hasRole('admin');
}
```

### File Naming Convention

Use consistent file naming:

```php
$file = $request->file('document');
$fileName = sprintf(
    '%s_%s_%s.%s',
    $user->id,
    $request->type,
    Carbon::now()->timestamp,
    $file->extension()
);

$path = $file->storeAs('documents', $fileName, 'private');
```

### Virus Scanning

Scan uploaded files for viruses:

```php
use Illuminate\Support\Facades\Storage;

$file = $request->file('document');
$tempPath = $file->store('temp');

// Scan with ClamAV or similar
$scanner = new VirusScanner();
if (!$scanner->isClean(Storage::path($tempPath))) {
    Storage::delete($tempPath);
    throw new \Exception('File contains malware');
}

// Move to permanent location
$path = Storage::move($tempPath, 'documents/' . $file->hashName());
```

### Document Versioning

Track document versions:

```php
public function uploadNewVersion(User $user, $file, $documentId)
{
    $oldDocument = $user->documents()->findOrFail($documentId);

    // Archive old version
    $oldDocument->update(['is_archived' => true]);

    // Create new version
    $path = $file->store('documents', 'private');

    return $user->documents()->create([
        'type' => $oldDocument->type,
        'title' => $oldDocument->title,
        'file_path' => $path,
        'file_type' => $file->extension(),
        'file_size' => $file->getSize(),
        'issued_at' => $oldDocument->issued_at,
        'expires_at' => $oldDocument->expires_at,
        'version' => $oldDocument->version + 1,
        'previous_version_id' => $oldDocument->id,
    ]);
}
```

## Related Documentation

- [Working with Credentials](10-credentials.md) - Link documents to credentials
- [Advanced Queries](06-advanced-queries.md) - Complex query patterns
- [Best Practices](07-best-practices.md) - Security and file handling
