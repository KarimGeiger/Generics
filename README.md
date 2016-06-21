# Generic Arrays in PHP

[![codecov](https://codecov.io/gh/KarimGeiger/Generics/branch/master/graph/badge.svg)](https://codecov.io/gh/KarimGeiger/Generics)
[![Build Status](https://travis-ci.org/KarimGeiger/Generics.svg?branch=master)](https://travis-ci.org/KarimGeiger/Generics)

PHP is currently stacking up some missing language features. With PHP 7 we've even got scalar type declarations now.

But there's one pretty important thing missing: As soon as you start working with arrays, everything gets lost. You then
can't be sure if the value or key you're retrieving is of the correct type. Worry no more! With this simple generics
classes you finally can relax.

## How does it work?

It's pretty easy. You create either a ``Generics\Dictionary`` object, if you'd like to control the types for keys and
values, or, if you just care about values, create a ``Generics\ArrayList`` object of whatever types you want:

```php
$list = new Generics\Dictionary('string', 'double', ['foo' => 1.5, 'bar' => 13.37]);
```

And then you're going to use it:

```php
$list['baz'] = 3.14;

var_dump($list->toArray());
/// ['foo' => 1.5, 'bar' => 13.37, 'baz' => 3.14]
```

But, as soon as something bad happens... 

```php
$list['sample'] = 1;
// Generics\Exceptions\InvalidTypeException: Type must be double, but integer was given.
```

Easy, huh? And of course, this will not only work with PHP types, but with your own classes as well.
The first argument on the constructor will target the key-type, the second argument will target the value-type.
As a third option, you may pre-fill your list.

And if you prefer a simple List having integer keys and generic values:

```php
$newList = new Generics\ArrayList(YourObject::class);
$newList[] = new YourObject();
```

If you're familiar with other languages, think of it as ``Generics\Dictionary<TKey, TValue>(data);`` and
``Generics\ArrayList<TValue>(data)``

## Features

This library is as small as it gets. No dependencies. Just plain PHP7. And to keep you safe: You'll get a code coverage
of 100%, since it's important that everything works just as expected.

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