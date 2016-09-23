# N4B Push
- [Concept](#concept)
- [Using Push](#using-push)
- [Urgency level](#urgency-level)

## Concept
N4B Push is the possibility for your Be-App server to send messages to the your Android BeApp without any prior request.
Push operations are described in your Be-App Manifest inside the _device_operations_ block.

## Using Push
This is an example of a N4B push for example in a console command or a cronjob:
```php
<?php

use N4B\Push;

// Create the push
$push = new Push('mybeapp', 1337, 1, 'MY5UP3r53Cr37K3Y');

// Send a push message 
$report = $push->send('myNotification', ['userId1'], ['event' => 'update'], Push::N4B_PUSH_URGENCY_HIGH);

// Print the sending report
print_r($report);
```
Let's explain it. The first step is to create the **push** instance. The arguments are the same as a webhook: the Be-App _name_, 
_id_, _version_ and _secret key_.

Then you can send your message with the `send` method. The arguments are the _operation_ name, an array of 
one or more _user unique identifier_, the associative array of _parameters_ and the _level of urgency_.

Then method returns an assosiative array of message status with the unique id of each user as key.

## Urgency level
N4B allows three types of _push urgency levels_. You can use `N4B\Push` class constants 
to choose the desired level:
- `N4B\Push::N4B_PUSH_URGENCY_HIGH`: The Android client has data connectivity or fallback on Be-Bound mode (default)
- `N4B\Push::N4B_PUSH_URGENCY_BEBOUND_ONLY`: Be-Bound mode right away
- `N4B\Push::N4B_PUSH_URGENCY_DATA_ONLY`: The Android client has data connectivity or give up

&larr; [Webhook](01-webhook.md)