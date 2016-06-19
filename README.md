# Generics in PHP

PHP is currently stacking up some missing language features. With PHP 7 we've even got scalar type definitions now.

But there's one pretty important thing missing: As soon as you start working with arrays, everything gets lost. You then
can't be sure if the value or key you're retrieving is of the correct type. Worry no more! With this simple generics
class you finally can relax.

## How does it work?

It's pretty easy. You create a ``GenericDictionary`` of whatever types you want:

```php
$list = new Generics\GenericDictionary('string', 'integer', ['foo', 'bar']);
```

And then you're going to use it:

```php
$list[] = 'baz';

var_dump($list->toArray());
/// [0 => 'foo', 1 => 'bar', 2 => 'baz']
```

But, as soon as something bad happens... 

```php
$list['invalid'] = 'string';
// Generics\Exceptions\InvalidTypeException: Type must be integer, but string was given.
```

Easy, huh? And of course, this will work with values as well as with keys. The first argument on the constructor will
target the value, the second argument will target the key. As a third option, you may pre-fill your list.

Of course, custom classes as types work as well:

```php
$newList = new Generics\GenericDictionary(YourObject::class, 'string');
$newList['key'] = new YourObject();
```

If you're familiar with other languages, think of it as ``Generic<TValue, TKey>(data);``.

## Features

This library is as small as it gets. No dependencies. Just plain PHP7. And to keep you safe: You'll get a code coverage
of 100% percent, since it's important that everything works just as expected.

## Usage

Add the package to your ``require`` section in the ``composer.json``-file and update your project.

```json
"require": {
    "karimgeiger/generics": "1.0.x-dev"
}
```

```sh
composer update
```

## Contribution

Like this project? Want to contribute? Awesome! Feel free to open some Pull requests or just an Issue. I won't bite :)