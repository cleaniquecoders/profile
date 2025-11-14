# Security Policy

## Supported Versions

We release patches for security vulnerabilities in the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

We take the security of the Profile package seriously. If you discover a security vulnerability, please follow these steps:

### 1. **Do Not** Disclose Publicly

Please do not open a public GitHub issue for security vulnerabilities. Public disclosure may put the entire community at risk.

### 2. Report Privately

Send a detailed report to our security team at:

**Email:** [nasrulhazim.m@gmail.com](mailto:nasrulhazim.m@gmail.com)

**Subject:** [SECURITY] Profile Package Vulnerability Report

### 3. Include Detailed Information

Your report should include:

- **Type of vulnerability** (e.g., SQL injection, XSS, authentication bypass)
- **Affected versions** of the package
- **Step-by-step instructions** to reproduce the vulnerability
- **Proof of concept** or exploit code (if available)
- **Potential impact** of the vulnerability
- **Suggested fix** (if you have one)

#### Example Report Template

```markdown
**Vulnerability Type:** SQL Injection

**Affected Versions:** 1.0.0 - 1.2.3

**Description:**
The address search functionality is vulnerable to SQL injection through
the city parameter.

**Steps to Reproduce:**
1. Create a user with the HasProfile trait
2. Call $user->addresses()->where('city', $_GET['city'])->get()
3. Pass malicious SQL: ?city='; DROP TABLE addresses; --

**Impact:**
An attacker could execute arbitrary SQL queries, potentially leading to
data breach or data loss.

**Suggested Fix:**
Use parameterized queries or Laravel's query builder properly.

**Proof of Concept:**
[Attach code or screenshots demonstrating the vulnerability]
```

### 4. What to Expect

After submitting a vulnerability report:

- **Within 48 hours:** We will acknowledge receipt of your report
- **Within 7 days:** We will provide an initial assessment and timeline
- **Ongoing:** We will keep you updated on our progress
- **Upon resolution:** We will publicly disclose the vulnerability (with your permission)

## Security Update Process

### For Users

When a security update is released:

1. **Security Advisory** will be published on GitHub
2. **New version** will be released immediately
3. **Notification** will be sent to package users
4. **Upgrade guide** will be provided if needed

### Update Priority

Security updates are classified by severity:

| Severity | Response Time | Description |
|----------|--------------|-------------|
| **Critical** | 24-48 hours | Immediate action required. Active exploitation possible. |
| **High** | 7 days | Significant risk. Should update as soon as possible. |
| **Medium** | 30 days | Moderate risk. Update during next maintenance window. |
| **Low** | 90 days | Minor risk. Can be included in regular updates. |

## Security Best Practices

When using the Profile package, follow these security best practices:

### 1. Input Validation

Always validate and sanitize user input before storing in profile models:

```php
use Illuminate\Support\Facades\Validator;

$validator = Validator::make($request->all(), [
    'email' => 'required|email|max:255',
    'phone_number' => 'required|regex:/^\+?[1-9]\d{1,14}$/',
    'url' => 'required|url|max:255',
]);

if ($validator->fails()) {
    // Handle validation errors
}

$user->emails()->create($validator->validated());
```

### 2. Mass Assignment Protection

Ensure your models have proper `$fillable` or `$guarded` properties:

```php
// Good - Explicit fillable fields
protected $fillable = ['email', 'is_default'];

// Or use guarded to protect specific fields
protected $guarded = ['id', 'created_at', 'updated_at'];
```

### 3. Authorization

Always check user permissions before allowing profile modifications:

```php
// Using Laravel Policy
$this->authorize('update', $address);

// Or manual check
if ($request->user()->cannot('update', $address)) {
    abort(403);
}
```

### 4. SQL Injection Prevention

The package uses Eloquent ORM which prevents SQL injection by default. However, avoid using raw queries:

```php
// Good - Uses parameter binding
$emails = Email::where('email', $searchTerm)->get();

// Bad - Vulnerable to SQL injection
$emails = Email::whereRaw("email = '$searchTerm'")->get();

// If you must use raw queries, use bindings
$emails = Email::whereRaw('email = ?', [$searchTerm])->get();
```

### 5. XSS Prevention

When displaying profile data in views, always escape output:

```blade
{{-- Good - Escaped output --}}
{{ $user->email }}

{{-- Bad - Unescaped output --}}
{!! $user->email !!}
```

### 6. Sensitive Data

Be cautious with sensitive profile information:

- **Encrypt** sensitive fields if needed
- Use **HTTPS** for all communications
- Implement **rate limiting** for profile API endpoints
- Log access to sensitive profile data
- Follow **GDPR** and data protection regulations

```php
// Example: Encrypting sensitive data
protected $casts = [
    'bank_account_number' => 'encrypted',
];
```

### 7. API Rate Limiting

Implement rate limiting for profile-related API endpoints:

```php
// In routes/api.php
Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
    Route::apiResource('users.addresses', AddressController::class);
    Route::apiResource('users.emails', EmailController::class);
});
```

## Vulnerability Disclosure Policy

### Coordinated Disclosure

We practice coordinated disclosure:

1. **Report received** - We acknowledge and begin investigation
2. **Fix developed** - We create and test a security patch
3. **Patch released** - Security update published
4. **Public disclosure** - After users have time to update (typically 30 days)

### Credit and Recognition

We believe in recognizing security researchers who help us:

- **Security advisory** will credit the reporter (with permission)
- **Hall of Fame** for significant contributions
- **CVE assignment** for qualifying vulnerabilities
- **Bug bounty** (if applicable)

## Security Hall of Fame

We thank the following security researchers for their responsible disclosure:

(No vulnerabilities have been reported yet)

## Additional Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)

## Contact

For security concerns or questions:

- **Email:** [nasrulhazim.m@gmail.com](mailto:nasrulhazim.m@gmail.com)
- **Subject Line:** [SECURITY] Your Message

---

Last Updated: 15 November 2025
