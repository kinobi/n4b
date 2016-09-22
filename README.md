# N4B PHP Library

Simple library  to quickly implement a N4B Be-App PHP backend

[![Latest Stable Version](https://img.shields.io/packagist/v/n4b/n4b.svg)](https://packagist.org/packages/n4b/n4b)
[![Build Status](https://img.shields.io/travis/kinobi/n4b/master.svg)](https://travis-ci.org/kinobi/n4b)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/5960a9bc-9e87-4429-a714-b2ee05c4feac.svg?maxAge=2592000)]()
[![Dependencies](https://img.shields.io/versioneye/d/user/projects/57e0c530bd6fa6004e11e634.svg)](https://www.versioneye.com/user/projects/57e0c530bd6fa6004e11e634?child=summary)
[![GitHub issues](https://img.shields.io/github/issues/kinobi/n4b.svg)](https://github.com/kinobi/n4b/issues)
[![License](https://img.shields.io/packagist/l/n4b/n4b.svg)](https://packagist.org/packages/n4b/n4b)

## Why ?

N4B PHP will allow you to concentrate your development only on your business domain. Authentication, 
operations dispatching, N4B Errors, formatting of the responses are handled by the library.

### What is N4B by Be-Bound ?
[Be-Bound's N4B platform](https://n4b.io) helps you develop Android applications with the possibility to **reach the Next 4 Billion** (N4B) users that are still not connected to the Internet. 
By integrating [Be-Bound](https://www.be-bound.com)’s SDK into your Android apps, and implementing your Be-App Webhooks, **your users will stay connected to your apps even when there is no internet**.


### Learn More about N4B by Be-Bound
- [N4B Documentation](http://doc.n4b.io)
- [N4B Developer Console](https://dev.n4b.io)


## Installation
N4B PHP works with PHP 5.6.0 or newer.

It's recommended that you use [Composer](https://getcomposer.org/) to install N4B PHP.
```bash
$ composer require n4b/n4b
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


## Change log
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Testing
``` bash
$ composer test
```


## About

### Security, bugs and feature requests
Bugs and feature request are tracked on [GitHub](https://github.com/kinobi/n4b/issues)

If you discover any security related issues, please email lbrianto@be-bound.com instead of using the issue tracker.


### Author
Lionel Brianto - <lbrianto@be-bound.com> - <http://twitter.com/kinobiweb><br />
<!--See also the list of [contributors](https://github.com/kinobi/n4b/contributors) which participated in this project.-->


### License
N4B PHP is licensed under the MIT License - see the `LICENSE` file for details
