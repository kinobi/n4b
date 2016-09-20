# N4B PHP - PHP Library for N4B by Be-Bound [![Build Status](https://img.shields.io/travis/kinobi/n4b/master.svg)](https://travis-ci.org/kinobi/n4b)

[![Latest Stable Version](https://img.shields.io/packagist/v/n4b/n4b.svg)](https://packagist.org/packages/n4b/n4b)
[![License](https://img.shields.io/packagist/l/n4b/n4b.svg)](https://packagist.org/packages/n4b/n4b)

A simple library to quickly implement a N4B BeApp PHP backend

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install N4B PHP.

```bash
$ composer require n4b/n4b "^1.0"
```

This will install N4B PHP and all required dependencies.

## Usage

Create an index.php file with the following contents:

```php
<?php

require 'vendor/autoload.php';

$n4b = new N4B\Webhook('mybeapp', 1337, 1, 'MY5UP3r53Cr37K3Y');

$n4b->add('myOperation', function($params, $transport, $userId]) {
	return ['uppercaseString', strtoupper($params['someString'])];
});

$n4b->run();
```

## Documentation

- [Webhook Instructions](doc/01-webhook.md)
- [Push Instructions](doc/02-push.md)

## About

### Requirements

- N4B PHP works with PHP 5.6.0 or newer.

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/kinobi/n4b/issues)

### Learn More about N4B by Be-Bound

Learn more at these links:

- [Website](https://n4b.io)
- [Documentation](http://doc.n4b.io)
- [Developer Console](https://dev.n4b.io)
- [Be-Bound](https://www.be-bound.com)

### Author

Lionel Brianto - <lbrianto@be-bound.com> - <http://twitter.com/kinobiweb><br />
<!--See also the list of [contributors](https://github.com/kinobi/n4b/contributors) which participated in this project.-->

### License

N4B PHP is licensed under the MIT License - see the `LICENSE` file for details