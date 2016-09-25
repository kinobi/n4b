# Log

As per release 1.1.0 you can set a PSR-3 compatible logger to your N4B PHP webhook or push.

## PSR-3
PSR-3 is a common interface for PHP logging libraries. 

More information available on the PHP-FIG website: [PSR-3 Logger Interface](http://www.php-fig.org/psr/psr-3/)

## Set a logger
`N4B\Webhook` and `N4B\Push` implements `Psr\Log\LoggerAwareInterface`, so adding a PSR-3 logger is as simple as:
```php
<?php

[...]

// $logger implements Psr\Log\LoggerInterface
$n4b->setLogger($logger);

[...]

```

## Choose a logger
The suggested logger package is [monolog/monolog](https://packagist.org/packages/monolog/monolog). 

But you can choose any package of this [list of packages providing psr/log-implementation](https://packagist.org/providers/psr/log-implementation).

&larr; [Push](02-push.md)
