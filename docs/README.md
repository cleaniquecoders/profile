# Profile Package Documentation

Welcome to the comprehensive documentation for the Profile package. This package provides a flexible solution for managing profile information (addresses, emails, phone numbers, websites, and bank accounts) using Laravel's polymorphic relationships.

## 📚 Documentation Structure

### [01. Getting Started](01-getting-started/)

Everything you need to get the package installed and configured.

- **[Installation](01-getting-started/01-installation.md)** - Install the package, publish migrations, and run seeders
- **[Configuration](01-getting-started/02-configuration.md)** - Configure models, seeders, and polymorphic types
- **[Quick Start](01-getting-started/03-quick-start.md)** - Basic examples to get you started quickly

### [02. Architecture](02-architecture/)

Understand how the package works under the hood.

- **[Overview](02-architecture/01-overview.md)** - High-level architecture and design principles
- **[Polymorphic Relationships](02-architecture/02-polymorphic-relationships.md)** - Understanding polymorphic design
- **[Traits](02-architecture/03-traits.md)** - Available traits and their purpose
- **[Models](02-architecture/04-models.md)** - Model structure and relationships
- **[Database Schema](02-architecture/05-database-schema.md)** - Database design and tables

### [03. Usage](03-usage/)

Detailed guides on using each feature of the package.

#### Core Profile Features

- **[Working with Addresses](03-usage/01-addresses.md)** - Create, retrieve, update, and delete addresses
- **[Working with Emails](03-usage/02-emails.md)** - Manage email addresses with defaults
- **[Working with Phones](03-usage/03-phones.md)** - Handle phone numbers with types (mobile, home, office, etc.)
- **[Working with Websites](03-usage/04-websites.md)** - Manage website URLs and social media profiles
- **[Working with Bank Accounts](03-usage/05-bank-accounts.md)** - Handle banking information securely

#### Extended Profile Features

- **[Working with Social Media](03-usage/08-social-media.md)** - Manage social media profiles (LinkedIn, GitHub, Twitter, etc.)
- **[Working with Emergency Contacts](03-usage/09-emergency-contacts.md)** - Store emergency contact information with relationships
- **[Working with Professional Credentials](03-usage/10-credentials.md)** - Manage licenses, certifications, and professional qualifications
- **[Working with Documents](03-usage/11-documents.md)** - Store and manage document attachments (passports, IDs, certificates)

#### Advanced Topics

- **[Advanced Queries](03-usage/06-advanced-queries.md)** - Complex query patterns and examples
- **[Best Practices](03-usage/07-best-practices.md)** - Tips and recommendations

### [04. API Reference](04-api-reference/)

Complete API documentation for all classes and methods.

- **[Traits API](04-api-reference/01-traits-api.md)** - All available traits and their methods
- **[Models API](04-api-reference/02-models-api.md)** - All models with properties and relationships
- **[Query Scopes](04-api-reference/03-query-scopes.md)** - Available query scopes and filters
- **[Configuration](04-api-reference/04-configuration-api.md)** - Configuration options reference

## 🚀 Quick Links

### Common Tasks

- [Install the package](01-getting-started/01-installation.md)
- [Add profile to a model](01-getting-started/03-quick-start.md#basic-setup)
- [Create an address](03-usage/01-addresses.md#creating-addresses)
- [Add phone numbers](03-usage/03-phones.md#creating-phone-numbers)
- [Query profile information](03-usage/06-advanced-queries.md)

### Key Concepts

- [Understanding polymorphic relationships](02-architecture/02-polymorphic-relationships.md)
- [Choosing the right traits](02-architecture/03-traits.md)
- [Database schema design](02-architecture/05-database-schema.md)

### Advanced Topics

- [Custom models and extensions](01-getting-started/02-configuration.md#custom-models)
- [Performance optimization](03-usage/06-advanced-queries.md#performance-optimization)
- [Security best practices](03-usage/07-best-practices.md#security)

## 📖 Package Overview

### What is Profile Package?

Profile is a Laravel package that provides a reusable solution for managing common profile information across different entities in your application. Instead of creating separate tables for each entity (users, companies, employees, vendors), you can use polymorphic relationships to share profile data.

### Key Features

- **Polymorphic Design** - One set of tables serves all entities
- **Trait-Based** - Pick only the features you need
- **Configurable** - Customize models and behavior
- **UUID Support** - Unique identifiers for external integrations
- **Soft Deletes** - Maintain data audit trail
- **Type Safety** - Proper relationships and query scopes

### Available Traits

- **`HasProfile`** - Combines all profile traits (core + extended)
- **`Addressable`** - Manage physical addresses
- **`Emailable`** - Manage email addresses
- **`Phoneable`** - Manage phone numbers with types
- **`Websiteable`** - Manage website URLs
- **`Bankable`** - Manage bank account information
- **`Socialable`** - Manage social media profiles (NEW)
- **`EmergencyContactable`** - Manage emergency contacts (NEW)
- **`Credentialable`** - Manage professional credentials (NEW)
- **`Documentable`** - Manage document attachments (NEW)

### Supported Data

#### Core Profile Data

- **Addresses** - Primary, secondary, city, state, postcode, country
- **Emails** - Multiple emails with default flag
- **Phones** - Multiple phones with types (Home, Mobile, Office, Fax, Other)
- **Websites** - Multiple URLs with default flag
- **Bank Accounts** - Account number, holder name, bank details

#### Extended Profile Data

- **Social Media** - Platform accounts with verification status (LinkedIn, GitHub, Twitter, etc.)
- **Emergency Contacts** - Name, relationship, phone, email with priority flag
- **Credentials** - Licenses, certifications, diplomas with expiration tracking
- **Documents** - File attachments with metadata and expiration tracking

#### Reference Data

- **Countries** - Preloaded country list
- **Banks** - Bank information
- **Phone Types** - Phone number categories

## 💡 Usage Example

```php
use CleaniqueCoders\Profile\Concerns\HasProfile;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasProfile;
}

// Create profile information
$user->addresses()->create([
    'primary' => '123 Main Street',
    'city' => 'Kuala Lumpur',
    'postcode' => '50088',
    'country_id' => 1,
]);

$user->phones()->create([
    'phone_number' => '+60123456789',
    'phone_type_id' => PhoneType::MOBILE,
    'is_default' => true,
]);

$user->emails()->create([
    'email' => 'john@example.com',
    'is_default' => true,
]);

// Query profile information
$addresses = $user->addresses;
$mobilePhones = $user->phones()->mobile()->get();
$defaultEmail = $user->emails()->where('is_default', true)->first();
```

## 🔧 Requirements

- PHP ^8.3 or ^8.4
- Laravel ^11.0 or ^12.0
- Composer

## 📦 Installation

```bash
composer require cleaniquecoders/profile
php artisan vendor:publish --tag=profile-migrations
php artisan migrate
php artisan profile:seed
```

[Full installation guide →](01-getting-started/01-installation.md)

## 🤝 Contributing

Contributions are welcome! Please ensure your code follows the package's coding standards and includes appropriate tests.

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

## 🆘 Support

- [Report Issues](https://github.com/cleaniquecoders/profile/issues)
- [View Source Code](https://github.com/cleaniquecoders/profile)

## 🔍 Need Help?

- Start with the [Quick Start Guide](01-getting-started/03-quick-start.md)
- Check the [Usage Examples](03-usage/)
- Review the [Best Practices](03-usage/07-best-practices.md)
- Browse the [API Reference](04-api-reference/)
