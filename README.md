## Profile

Profile is a package to store basic information - addresses, phone numbers, emails, and websites using [Polymorph](https://laravel.com/docs/6.x/eloquent-relationships#polymorphic-relationships) approach.

[![Latest Stable Version](https://poser.pugx.org/cleaniquecoders/profile/v/stable)](https://packagist.org/packages/cleaniquecoders/profile) [![Total Downloads](https://poser.pugx.org/cleaniquecoders/profile/downloads)](https://packagist.org/packages/cleaniquecoders/profile) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cleaniquecoders/profile/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cleaniquecoders/profile/?branch=master) [![License](https://poser.pugx.org/cleaniquecoders/profile/license)](https://packagist.org/packages/cleaniquecoders/profile)

## Installation

1. In order to install Profile in your Laravel project:

```
$ composer require cleaniquecoders/profile
```

2. Then in your `config/app.php` add the following to the `providers` key:

```php
\CleaniqueCoders\Profile\ProfileServiceProvider::class,
```

Publish factory files:

```
$ php artisan vendor:publish --tag=profile-factories
```

Publish migrations files:

```
$ php artisan vendor:publish --tag=profile-migrations
```

Publish seed files:

```
$ php artisan vendor:publish --tag=profile-seeds
```

Then run:

```
$ php artisan migrate
```

Then seed the Country and Phone Types data with:

```
$ php artisan profile:seed
```

#### Configuration

Now you are able to configure your own models and type name. See `config/profile.php`.

You may want to define your own seeders for `profile:seed` in `config/profile.php`.

### Available Polymorph Traits

User Cases: 

1. A company has addresses, phone numbers, emails and websites.
2. An employee has addresses, phone numbers, emails and websites.

This lead us to use Polymorph to tackle the issue of similarity in data stored.

#### Setup

Available traits for polymorph:

1. `CleaniqueCoders\Profile\Traits\Morphs\Addressable`
2. `CleaniqueCoders\Profile\Traits\Morphs\Emailable`
3. `CleaniqueCoders\Profile\Traits\Morphs\Phoneable`
4. `CleaniqueCoders\Profile\Traits\Morphs\Websiteable`
5. `CleaniqueCoders\Profile\Traits\Morphs\Bankable`

For most common setup for entity is to use `HasProfile` trait.

`HasProfile` trait consist of:

1. `CleaniqueCoders\Profile\Traits\Morphs\Addressable`
2. `CleaniqueCoders\Profile\Traits\Morphs\Emailable`
3. `CleaniqueCoders\Profile\Traits\Morphs\Phoneable`
4. `CleaniqueCoders\Profile\Traits\Morphs\Websiteable`

```php

namespace App;

use CleaniqueCoders\Profile\Traits\HasProfile;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use HasProfile;
}
```

#### Usage

**Create a record for each profile**

```php
$user->addresses()->create([
	'primary' => '9 miles, Sungei Way',
	'secondary' => 'P.O.Box 6503, Seri Setia',
	'city' => 'Petaling Jaya',
	'postcode' => '46150',
	'state' => 'Selangor',
	'country_id' => config('profile.providers.country.model')::name('Malaysia')->first()->id
]);
```

```php 
$user->phones()->create([
    'phone_number'  => '+6089259167',
    'is_default'    => true,
    'phone_type_id' => PhoneType::HOME,
]);
$user->phones()->create([
    'phone_number'  => '+60191234567',
    'is_default'    => true,
    'phone_type_id' => PhoneType::MOBILE,
]);
$user->phones()->create([
    'phone_number'  => '+60380001000',
    'is_default'    => true,
    'phone_type_id' => PhoneType::OFFICE,
]);
$user->phones()->create([
    'phone_number'  => '+60380001000',
    'is_default'    => true,
    'phone_type_id' => PhoneType::OTHER,
]);
$user->phones()->create([
    'phone_number'  => '+60380001001',
    'is_default'    => true,
    'phone_type_id' => PhoneType::FAX,
]);

// you can futher query using local scopes in phone models.
// get the first home phone number
$user->phones()->home()->first();
// get all the mobile phone numbers
$user->phones()->mobile()->get();
```

```php
$user->emails()->create([...]);
$user->websites()->create([...]);
$user->bankable()->create([...]);
```

**Get all records**

```php
$user->addresses;
$user->emails;
$user->phones;
$user->websites;
$user->banks;
```

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).