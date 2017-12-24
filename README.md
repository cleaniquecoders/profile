## About Your Package

Tell people about your package

## Installation

```
$ composer require clnqcdrs/profile 
```

## Usage

In case you want to modify the migration file:

```
$ php artisan vendor:publish --tag=profile-migrations
```

Run the following to publish factory files:

```
$ php artisan vendor:publish --tag=profile-factories
```

Else, you can just run the migration:

```
$ php artisan migrate
```

### Available Polymorph Traits

User Cases: 

1. A company has addresses, phone numbers and emails.
2. An employee has addresses, phone numbers and emails.

This lead us to use Polymorph to tackle the issue of similarity in data stored.

#### Setup

```php
use CLNQCDRS\Profile\Traits\HasProfile;

class User extends Authenticatable 
{
	use HasProfile;
```

`HasProfile` trait consist of:

1. `CLNQCDRS\Profile\Traits\Morphs\Addressable`
2. `CLNQCDRS\Profile\Traits\Morphs\Emailable`
3. `CLNQCDRS\Profile\Traits\Morphs\Phoneable`

#### Usage

```php
$user->addresses()->create([...])
$user->emails()->create([...])
$user->phones()->create([...])
```

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).