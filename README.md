# Platinium Bundle

This bundle provide a easy way to send a push message by using [Platinium](http://platinium.openium.fr/)

## Installation

Open a command console, enter your project directory and execute:

```bash
$ composer require openium/platinium-bundle
```

## Configuration

You need to add 4 information in the .env
```
###> openium/platinium-bundle ###
PLATINIUM_SERVER_ID=
PLATINIUM_SERVER_KEY=
PLATINIUM_SERVER_TOKEN_DEV=
PLATINIUM_SERVER_TOKEN_PROD=
###< openium/platinium-bundle ###
```

## Usage

Example :

```php
// set by dependency injection
$notifier = new PlatiniumNotifier(...);
// get number of future pushed devices
$deviceCount = $notifier->subscribe($groups, $langs, $langNotIn, $latitude, $longitude, $tolerance, $radius, $paramsBag, $badgeValue, $newsStand, $sound);
// send a push message
$pushSended = $notifier->notify($message, $groups, $langs, $langNotIn, $latitude, $longitude, $tolerance, $radius);
```