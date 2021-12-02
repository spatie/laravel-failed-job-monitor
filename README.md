# Get notified when a queued job fails

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-failed-job-monitor.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-failed-job-monitor)
![Test Status](https://img.shields.io/github/workflow/status/spatie/laravel-failed-job-monitor/run-tests?label=tests&style=flat-square)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-failed-job-monitor/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-failed-job-monitor)
[![StyleCI](https://styleci.io/repos/52006263/shield)](https://styleci.io/repos/52006263)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-failed-job-monitor.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-failed-job-monitor)

This package sends notifications if a queued job fails. Out of the box it can send a notification via mail and/or Slack. It leverages Laravel's native notification system.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-failed-job-monitor.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-failed-job-monitor)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

> For Laravel versions 5.8 and 6.x, use v3.x of this package.

You can install the package via composer:

``` bash
composer require spatie/laravel-failed-job-monitor
```
If you intend to use Slack notifications you should also install the guzzle client:

``` bash
composer require guzzlehttp/guzzle
```

The service provider will automatically be registered.

Next, you must publish the config file:

```bash
php artisan vendor:publish --tag=failed-job-monitor-config
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

If you want to customize the notification you can specify your own notification class in the config file.

```php
// config/failed-job-monitor.php
return [
    ...
    'notification' => \App\Notifications\CustomNotificationForFailedJobMonitor::class,
    ...
```

### Customizing the notifiable
 
The default notifiable class provided by this package use the `channels`, `mail` and `slack` keys from the `config` file to determine how notifications must be sent
 
If you want to customize the notifiable you can specify your own notifiable class in the config file.

```php
// config/failed-job-monitor.php
return [
    'notifiable' => \App\CustomNotifiableForFailedJobMonitor::class,
    ...
```

## Usage

If you configured the package correctly, you're done. You'll receive a notification when a queued job fails.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

A big thank you to [Egor Talantsev](https://github.com/spyric) for his help creating `v2` of the package.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
