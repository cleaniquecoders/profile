# Installation

This guide covers the complete installation process for the Profile package.

## Requirements

- PHP ^8.3 or ^8.4
- Laravel ^11.0 or ^12.0
- Composer

## Installation Steps

### 1. Install via Composer

Install the Profile package using Composer:

```bash
composer require cleaniquecoders/profile
```

### 2. Publish Migration Files

Publish the migration files to your application:

```bash
php artisan vendor:publish --tag=profile-migrations
```

This will publish the following migration files to your `database/migrations` directory:

- `create_addresses_table.php`
- `create_banks_table.php`
- `create_countries_table.php`
- `create_emails_table.php`
- `create_phone_types_table.php`
- `create_phones_table.php`
- `create_websites_table.php`

### 3. Run Migrations

Execute the migrations to create the necessary database tables:

```bash
php artisan migrate
```

### 4. Seed Default Data

Run the default seeders to populate reference data:

```bash
php artisan profile:seed
```

This will seed:

- **Countries**: List of countries with codes
- **Phone Types**: Home, Mobile, Office, Other, Fax
- **Banks**: List of Malaysian banks (can be customized)

## What's Next?

- [Configuration](02-configuration.md) - Learn how to configure the package
- [Quick Start](03-quick-start.md) - Start using the package with basic examples
