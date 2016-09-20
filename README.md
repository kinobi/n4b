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

$n4b->add('myOperation', function($params, $transport, $userId) {
	return ['uppercaseString', strtoupper($params['someString'])];
});

$n4b->run();
```

You may quickly test this using the built-in PHP server:
```bash
$ php -S localhost:8000
```

Then running the cURL POST request below (or use the [Postman](https://www.getpostman.com) button) will now return a well formated N4B response "{"params":["uppercaseString","THIS VERY INTERESTING TEXT WILL BE OUTPUT IN UPPERCASE"]}".

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/02052ba017f0c817035e)

```bash
curl --request POST \
  --url http://localhost:8000/ \
  --header 'authorization: Basic bXliZWFwcF8xMzM3Ok1ZNVVQM3I1M0NyMzdLM1k=' \
  --header 'cache-control: no-cache' \
  --header 'content-type: application/json' \
  --data '{"transport":"web","userId":"98e866b2-3d39-11e6-97dc-0cc47a77819c","moduleId":1337,"moduleName":"mybeapp","moduleVersion":1,"operation":"myOperation","params":{"someString":"this very interesting text will be output in uppercase"}}'
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