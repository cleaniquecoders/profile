# Changelog

All notable changes to `Profile` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-11-15

### Added

#### Core Features

- Initial release of Profile package for Laravel
- Polymorphic design allowing reusable profile tables for any model
- `HasProfile` trait combining all profile functionalities
- `Addressable` trait for managing physical addresses
- `Emailable` trait for managing email addresses
- `Phoneable` trait for managing phone numbers with type support
- `Websiteable` trait for managing website URLs
- `Bankable` trait for managing bank account information

#### Models

- `Address` model with support for multi-line addresses, city, state, postcode, and country
- `Email` model with default flag support
- `Phone` model with phone type categorization (home, mobile, office, fax, other)
- `Website` model with default flag support
- `Bank` model with comprehensive bank information (code, swift code, addresses)
- `BankAccount` model linking users to banks with account details
- `Country` model for address association
- `PhoneType` model for categorizing phone numbers

#### Database

- Migration stubs for all profile tables
- Polymorphic relationship columns (`profileable_type`, `profileable_id`)
- UUID columns for external integrations
- Soft delete support for all models
- Proper indexing for performance optimization
- Foreign key constraints for data integrity

#### Query Features

- Query scopes for phone types (`mobile()`, `home()`, `office()`, `fax()`, `other()`)
- Default record filtering
- Soft delete queries

#### Configuration

- Configurable model names via `profile.php` config file
- Configurable polymorphic type names
- Table name customization support

#### Seeders

- `BankSeeder` with Malaysian bank data
- `CountrySeeder` with comprehensive country list
- `PhoneTypeSeeder` with standard phone types

#### Testing

- Pest PHP testing framework integration
- PHPStan static analysis at level 9
- Laravel Pint code formatting
- Comprehensive feature tests for:
  - Address creation and management
  - Email operations with default handling
  - Phone number management with type filtering
  - Website management
  - Bank and bank account operations
  - Country and phone type models
- Test coverage for polymorphic relationships
- Soft delete testing

#### CI/CD

- GitHub Actions workflow for automated testing
- Automated code style fixing with Pint
- Automated changelog updates
- Dependabot integration for dependency updates
- Dependabot auto-merge workflow

#### Documentation

- Comprehensive README with quick start guide
- Detailed installation instructions
- Configuration guide
- Usage examples for all features
- Full documentation site structure (`docs/`)
- Getting Started section (Installation, Configuration, Quick Start)
- Architecture documentation (Overview, Polymorphic Relationships, Traits, Models, Database Schema)
- Usage guides (Addresses, Emails, Phones, Websites, Bank Accounts, Advanced Queries, Best Practices)
- API Reference (Traits API, Models API, Query Scopes, Configuration)

### Requirements

- PHP ^8.3 | ^8.4
- Laravel ^11.0 | ^12.0

### Dependencies

- spatie/laravel-package-tools ^1.16
- cleaniquecoders/traitify ^1.0

---

## Release Notes

### What's New in v1.0.0

This is the initial stable release of the Profile package, providing a comprehensive solution for managing various types of profile information in Laravel applications.

**Key Highlights:**

1. **Flexible Architecture** - Use only what you need with trait-based design
2. **Production Ready** - Comprehensive test coverage and static analysis
3. **Well Documented** - Extensive documentation with real-world examples
4. **Laravel Best Practices** - Follows Laravel conventions and patterns
5. **Type Safe** - Full PHP type hints and static analysis

**Perfect For:**

- User profile management systems
- CRM applications
- Contact management systems
- Multi-tenant applications
- Any application requiring structured profile data

**Getting Started:**

```bash
composer require cleaniquecoders/profile
php artisan vendor:publish --tag=profile-migrations
php artisan migrate
php artisan profile:seed
```

### Upgrade Guide

This is the first release, so no upgrade is necessary.

### Breaking Changes

None - this is the initial release.

---

[1.0.0]: https://github.com/cleaniquecoders/profile/releases/tag/1.0.0
