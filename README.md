# Get notified when a queued job fails

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-failed-job-monitor.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-failed-job-monitor)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-failed-job-monitor/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-failed-job-monitor)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/f2aaa07e-2960-4ed5-a130-626e990fef3f.svg?style=flat-square)](https://insight.sensiolabs.com/projects/f2aaa07e-2960-4ed5-a130-626e990fef3f)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-failed-job-monitor.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-failed-job-monitor)
[![StyleCI](https://styleci.io/repos/52006263/shield)](https://styleci.io/repos/52006263)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-failed-job-monitor.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-failed-job-monitor)

This package sends notifications if a queued job fails. Out of the box it can send a notification via mail and/or Slack.

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
    Spatie\FailedJobMonitor\ServiceProvider::class,
    ...
];
```

Next, you must publish the config file:

```bash
php artisan vendor:publish --provider="Spatie\FailedJobMonitor\ServiceProvider"
```

This is the contents of the default configuration file. 

```php
return [
    '*' => [
        'notifiable' => \App\User::class,
        'notification' => \Spatie\FailedJobMonitor\Notification::class,
        'via' => ['mail'],
        //'filter' => 'canBeNotifiedAboutFailedJobs'
    ],
];

```

Define `scopeCanBeNotifiedAboutFailedJobs` method in your `\App\User` class. If you don't have`\App\User` class, you can change `notifiable` property to yours correct value 

## Configuration

### Defining rules for jobs
By default Laravel Failed Job Monitor has only one rule with name `*`. This rule is applying to every failed job.

If you have some job and want notify about fail not only developers but someone else, you can define rule for it: 

```php
return [
    \App\Jobs\ImportantJob::class => [
        'notifiable' => \App\User::class,
        'notification' => \App\Notifications\ImportantJonFailed::class,
        'via' => ['mail', 'slack'],
        'filter' => 'canBeNotifiedAboutFailedImportantJobs'
    ],
    '*' => [
        'notifiable' => \App\User::class,
        'notification' => \Spatie\FailedJobMonitor\Notification::class,
        'via' => ['mail'],
        //'filter' => 'canBeNotifiedAboutFailedJobs'
    ],
];
```

### Configuring rule for job
Each rule contains next properties:
- `notifiable` - notifiable class name like users, team, department and e.t.c
- `notification` - class name of Notification instance. Must extends `\Spatie\FailedJobMonitor\Notification` class
- `via` - set channels to use it can be skipped if you overwrite via method on notification
- `filter` - name of scope that will be applied to `notifiable` to get all records that should receive notification. By default: `canBeNotifiedAboutFailedJobs`. If you set `developers` as value, you should define `scopeDevelopers` function in notifiable record. More information about scopes you can find in [official Laravel documentation](https://laravel.com/docs/5.3/eloquent#query-scopes)

### Adding new channels
By default this package are support only `mail` and `slack` drivers (you can find more info about that driver in [official Laravel documentation](https://laravel.com/docs/5.3/notifications)).
 
If you want more channels you can create your own Notification class that will extend `\Spatie\FailedJobMonitor\Notification` and set it in `notification` property in any required rule

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

- [Jolita Grazyte](https://github.com/JolitaGrazyte)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
