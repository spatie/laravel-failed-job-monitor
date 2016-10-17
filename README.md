# Get notified when a queued job fails

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-failed-job-monitor.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-failed-job-monitor)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-failed-job-monitor/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-failed-job-monitor)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/f2aaa07e-2960-4ed5-a130-626e990fef3f.svg?style=flat-square)](https://insight.sensiolabs.com/projects/f2aaa07e-2960-4ed5-a130-626e990fef3f)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-failed-job-monitor.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-failed-job-monitor)
[![StyleCI](https://styleci.io/repos/52006263/shield)](https://styleci.io/repos/52006263)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-failed-job-monitor.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-failed-job-monitor)

This package sends notifications if a queued job fails. Out of the box it can send a notification via mail and/or Slack. It leverages [Laravel 5.3's notification capabilities](https://laravel.com/docs/5.3/notifications).

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-failed-job-monitor
```

Next up, the service provider must be registered:

```php
'providers' => [
    ...
    Spatie\FailedJobMonitor\FailedJobMonitorServiceProvider::class,
    ...
];
```

Next, you must publish the config file:

```bash
php artisan vendor:publish --provider="Spatie\FailedJobMonitor\FailedJobMonitorServiceProvider"
```

This is the contents of the default configuration file.  Here you can specify the notifiable to which the notifications should be sent. The default notifiable will use the variables specified in this config file.

```php
return [

    /**
     * The notification that will be sent when a job fails.
     */
    'notification' => \Spatie\FailedJobMonitor\Notification::class,

    /**
     * The notifiable to which the notification will be sent. The default
     * notifiable will use the mail and slack configuration specified
     * in this config file.
     */
    'notifiable' => \Spatie\FailedJobMonitor\Notifiable::class,

    /**
     * The channels to which the notification will be sent.
     */
    'channels' => ['mail', 'slack'],

    'mail' => [
        'to' => 'email@example.com',
    ],

    'slack' => [
        'webhook_url' => env('FAILED_JOB_SLACK_WEBHOOK_URL'),
    ],
];
``` 

## Configuration


### Customizing the notification
 
The default notification class provided by this package has support for mail and Slack. 

If you want to to customize the notification you can specify your own notification class in the config file.

```php
// config/laravel-failed-job-monitor.php
return [
    ...
    'notification' => \App\Notifications\CustomNotificationForFailedJobMonitor::class,
    ...
```

### Customizing the notifiable
 
The default notifiable class provided by this package use the `channels`, `mail` and `slack` keys from the `config` file to determine how notifications must be sent
 
If you want to customize the notifiable you can specify your own notifiable class in the config file.

```php
// config/laravel-failed-job-monitor.php
return [
    'notifiable' => \App\CustomNotifiableForFailedJobMonitor::class,
    ...
```

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment you are required to send us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

The best postcards will get published on the open source page on our website.

## Usage

If you configured the package correctly, you're done. You'll receive a notification when a queued job fails.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

A big thank you to [Egor Talantsev](https://github.com/spyric) for his help creating `v2` of the package.

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
