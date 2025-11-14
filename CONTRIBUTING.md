# Contributing to Profile

Thank you for considering contributing to the Profile package! We welcome contributions from the community and are grateful for your support.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
  - [Reporting Bugs](#reporting-bugs)
  - [Suggesting Enhancements](#suggesting-enhancements)
  - [Pull Requests](#pull-requests)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Testing Guidelines](#testing-guidelines)
- [Commit Guidelines](#commit-guidelines)
- [Documentation](#documentation)

## Code of Conduct

This project and everyone participating in it is governed by our [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to [nasrulhazim.m@gmail.com](mailto:nasrulhazim.m@gmail.com).

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When you create a bug report, include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples** (code snippets, configuration)
- **Describe the behavior you observed** and what you expected
- **Include your environment details** (PHP version, Laravel version, OS)

#### Example Bug Report

```markdown
**Bug Description:**
Address creation fails when country_id is null

**Steps to Reproduce:**
1. Create a User model with HasProfile trait
2. Call $user->addresses()->create(['primary' => '123 Main St'])
3. Error occurs

**Expected Behavior:**
Should create address with null country_id or use default

**Actual Behavior:**
Database constraint error

**Environment:**
- PHP: 8.3.0
- Laravel: 11.0
- Profile Package: 1.0.0
```

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion:

- **Use a clear and descriptive title**
- **Provide a detailed description** of the suggested enhancement
- **Explain why this enhancement would be useful** to most users
- **Provide examples** of how the feature would be used
- **List any alternatives** you've considered

### Pull Requests

We actively welcome your pull requests! Here's how to submit one:

1. **Fork the repository** and create your branch from `master`
2. **Make your changes** following our coding standards
3. **Add tests** for any new functionality
4. **Ensure all tests pass** (`composer test`)
5. **Run code formatting** (`composer format`)
6. **Run static analysis** (`composer analyse`)
7. **Update documentation** if needed
8. **Write a clear commit message** following our guidelines
9. **Submit the pull request**

#### Pull Request Process

1. Update the README.md or relevant documentation with details of changes
2. Update the CHANGELOG.md following [Keep a Changelog](https://keepachangelog.com/) format
3. The PR will be merged once you have the sign-off of at least one maintainer

## Development Setup

### Prerequisites

- PHP 8.3 or higher
- Composer
- Git

### Installation

1. Fork and clone the repository:

```bash
git clone https://github.com/YOUR_USERNAME/profile.git
cd profile
```

2. Install dependencies:

```bash
composer install
```

3. Run tests to ensure everything is working:

```bash
composer test
```

### Available Commands

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse

# Format code
composer format

# Check code style
composer check-format
```

## Coding Standards

We follow the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard and use [Laravel Pint](https://laravel.com/docs/pint) for code formatting.

### Key Principles

- **Write clean, readable code** with descriptive variable and method names
- **Keep methods small and focused** (Single Responsibility Principle)
- **Add PHPDoc comments** for all public methods and complex logic
- **Follow Laravel conventions** for naming and structure
- **Use type hints** for all parameters and return types
- **Avoid abbreviations** unless widely understood

### Example

```php
<?php

namespace CleaniqueCoders\Profile\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Email Model
 *
 * Represents an email address that can be associated with any model.
 */
class Email extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email',
        'is_default',
        'profileable_type',
        'profileable_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the parent profileable model.
     */
    public function profileable(): BelongsTo
    {
        return $this->morphTo();
    }
}
```

## Testing Guidelines

All code contributions must include tests. We use [Pest PHP](https://pestphp.com/) for testing.

### Writing Tests

- **Test one thing per test** - Keep tests focused and simple
- **Use descriptive test names** - The test name should explain what is being tested
- **Follow the Arrange-Act-Assert pattern**
- **Use factories** for creating test data
- **Clean up after tests** if needed

### Test Structure

```php
<?php

use CleaniqueCoders\Profile\Models\Email;
use CleaniqueCoders\Profile\Tests\Models\User;

it('can create an email with default flag', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $email = $user->emails()->create([
        'email' => 'test@example.com',
        'is_default' => true,
    ]);

    // Assert
    expect($email)
        ->email->toBe('test@example.com')
        ->is_default->toBeTrue();
});

it('can retrieve default email', function () {
    // Arrange
    $user = User::factory()->create();
    $user->emails()->create(['email' => 'primary@example.com', 'is_default' => true]);
    $user->emails()->create(['email' => 'secondary@example.com', 'is_default' => false]);

    // Act
    $defaultEmail = $user->emails()->where('is_default', true)->first();

    // Assert
    expect($defaultEmail->email)->toBe('primary@example.com');
});
```

### Test Coverage

- Aim for **80%+ code coverage** for new features
- **All public methods** must be tested
- Test **edge cases** and error conditions
- Test **relationships** and scopes

## Commit Guidelines

We follow [Conventional Commits](https://www.conventionalcommits.org/) for commit messages.

### Commit Message Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- **feat**: A new feature
- **fix**: A bug fix
- **docs**: Documentation only changes
- **style**: Code style changes (formatting, missing semicolons, etc.)
- **refactor**: Code changes that neither fix a bug nor add a feature
- **perf**: Performance improvements
- **test**: Adding or updating tests
- **chore**: Changes to build process or auxiliary tools

### Examples

```bash
feat(emails): add validation for email format

Add email format validation using Laravel's built-in validator.
This prevents invalid email addresses from being stored.

Closes #123

---

fix(addresses): handle null country_id gracefully

Previously, creating an address without country_id would throw an error.
Now it defaults to null and handles the case properly.

---

docs(readme): update installation instructions

Add steps for publishing configuration files.
```

## Documentation

Good documentation is crucial for the success of this package.

### What to Document

- **New features** - Add usage examples to the relevant docs section
- **API changes** - Update API reference documentation
- **Configuration options** - Document in configuration guide
- **Breaking changes** - Clearly mark in CHANGELOG and migration guide

### Documentation Structure

```
docs/
├── README.md                          # Documentation index
├── 01-getting-started/
│   ├── 01-installation.md
│   ├── 02-configuration.md
│   └── 03-quick-start.md
├── 02-architecture/
│   ├── 01-overview.md
│   ├── 02-polymorphic-relationships.md
│   └── ...
├── 03-usage/
│   ├── 01-addresses.md
│   ├── 02-emails.md
│   └── ...
└── 04-api-reference/
    ├── 01-traits-api.md
    └── ...
```

### Writing Style

- Use **clear, concise language**
- Include **code examples** for all features
- Add **warnings** for potential pitfalls
- Link to **related documentation**
- Keep it **up to date** with code changes

## Getting Help

- **GitHub Issues** - For bug reports and feature requests
- **GitHub Discussions** - For questions and general discussion
- **Email** - [nasrulhazim.m@gmail.com](mailto:nasrulhazim.m@gmail.com) for private inquiries

## Recognition

All contributors will be recognized in our:
- README.md Contributors section
- GitHub repository contributors page
- Release notes for significant contributions

Thank you for contributing to Profile! 🎉
