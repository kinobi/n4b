# N4B PHP Webhook
- [Concept](#concept)
- [Configuring a webhook](#configuring-a-webhook)
- [N4B Errors](#n4b-errors)
- [Webhook run options](#webhook-run-options)

## Concept
The `N4B\Webhook` is the entrypoint of the N4B requests on your server. 
You need to handle each operation described in your Be-App Manifest in the _server_operation_ block.

## Configuring a webhook
This is an example of a `N4B\Webhook` with two operations handled:
```php
<?php

use N4B\Webhook;

// Create the webhook
$n4b = new Webhook('mybeapp', 1337, 1, 'MY5UP3r53Cr37K3Y');

// Now add some operations handlers
$n4b->add('myOperation', function($params, $transport, $userId) {
	return ['uppercaseString' => strtoupper($params['someString'])];
});
$n4b->add('myOtherOperation', function($params, $transport, $userId) {
	return ['add' => $params['foo'] + $params['bar']];
});

// You can now wait for incoming requests to handle
$n4b->run();
```
Let's explain it. The first step is to create the `N4B\Webhook` instance. The arguments are the Be-App _name_, 
_id_, _version_ and _secret key_.

The second step consists in adding handlers for each _operation_ declare in the _server_operation_ block 
of your Be-App Manifest. This task is done by the method `add` of the `N4B\Webhook`. The arguments are 
the _operation_ name and a `callable` as **handler**. 

**The handler as to return an associative array of parameters as described in the Be-App Manifest.**

The signature of the `callable` can be documented as below:
```php
/**
 * N4B Operation handler 
 * 
 * @param array $params An associative array of request parameters described in the Be-App Manifest
 * @param string $transport The Be-Bound transport type "sms" or "web"
 * @param string $userId The user unique identifier
 *
 * @return array An associative array of response parameters described in the Be-App Manifest
 */
```

> Tip: The transport type helps you to adapt the behaviour of your Be-App depending on the connectivity available 
on the Android Client at request time.

In this example the `callable` is a simple [anonymous function](http://php.net/manual/en/functions.anonymous.php) 
but it can be as well:
- a simple callback
- a static class method call
- an object method call
- a static class method call
- an object implementing `__invoke`

More information in the PHP documentation: [Callbacks / Callables](http://php.net/manual/en/language.types.callable.php)

Finally you can run the webhook using its `run` method. [More](#webhook-run-options) on this after.

## N4B Errors
The Be-App Manifest allows you to define some **error codes** in your Be-App. 
**N4B PHP** simplify the return of these error codes, you just need to throw a `N4B\Error`. The argument is an 
error string defined in the Be-App Manifest. An example below:
```php
<?php

use N4B\Webhook;
use N4B\Error;

[...]

// myOperation with error handling
$n4b->add('myOperation', function($params, $transport, $userId) {
	if(empty($params['someString'])) {
		throw new Error("Err_emptyString");
	}
	return ['uppercaseString' => strtoupper($params['someString'])];
});

[...]
```

## Webhook run options
The `N4B\Webhook` `run` method can be configurated via an associative array of options like below:
```php
<?php

[...]

$options = [
    'authCheck' => true,
    'catchAll'  => true,
    'getResponse' => false
];

$n4b->run($options);
```
The options are:
- `authCheck` : a boolean to enable/disable the checking of the authentification, 
only recommended in local development (default to `true`)
- `catchAll` : a boolean to enable/disable the catching of all the handlers exceptions, 
useful in development (default to `true`)
- `getResponse` : a boolean to enable/disable the possibility to get the response as string (default to `false`)

[Push](02-push.md) &rarr;
