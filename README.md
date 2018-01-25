## About Your Package

[![Build Status](https://travis-ci.org/cleaniquecoders/profile.svg?branch=master)](https://travis-ci.org/cleaniquecoders/profile) [![Latest Stable Version](https://poser.pugx.org/cleaniquecoders/profile/version)](https://packagist.org/packages/cleaniquecoders/profile) [![Total Downloads](https://poser.pugx.org/cleaniquecoders/profile/downloads)](https://packagist.org/packages/cleaniquecoders/profile) [![License](https://poser.pugx.org/cleaniquecoders/profile/license)](https://packagist.org/packages/cleaniquecoders/profile)

## Installation

1. In order to install Profile in your Laravel project:

```
$ composer require cleaniquecoders/profile
```

2. Then in your `config/app.php` add the following to the `providers` key:

```php
\CleaniqueCoders\Profile\ProfileServiceProvider::class,
```

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

Then seed the Country and Phone Types data with:

```
$ php artisan profile:seed
```

### Available Polymorph Traits

User Cases: 

1. A company has addresses, phone numbers and emails.
2. An employee has addresses, phone numbers and emails.

This lead us to use Polymorph to tackle the issue of similarity in data stored.

#### Setup

```php

use CleaniqueCoders\Profile\Traits\HasProfile;

class User extends Authenticatable 
{
	use HasProfile;
}
```

`HasProfile` trait consist of:

1. `CleaniqueCoders\Profile\Traits\Morphs\Addressable`
2. `CleaniqueCoders\Profile\Traits\Morphs\Emailable`
3. `CleaniqueCoders\Profile\Traits\Morphs\Phoneable`

#### Usage

```php
$user->addresses()->create([...])
$user->emails()->create([...])
$user->phones()->create([...])
```

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).